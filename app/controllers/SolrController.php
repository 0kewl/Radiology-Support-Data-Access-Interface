<?php

use Solarium\Client;

class SolrController extends BaseController {

	/**
	 * Returns the Search page
	 * @return View index
	 */
	public function getIndex()
	{
		// Keywords used to populate the case comparison drop-down menu
		$keywords = SearchFieldEntity::getFields();

		return View::make('index', compact('keywords'));
	}

	/**
	 * Returns the Results page of a search query
	 * @return View results
	 */
	public function getResults()
	{
		// Get the search query
		$query = Input::get('q');
		$query = urldecode($query);

		// Get the cursor starting position
		$startPos = Input::get('start');

		if ($query == "*") {
			$query = "-id%3ARAD-bookmarks";
		}
		
		// Get a Solr client
		$client = $this->getSolrClient();

		$data = new SolrQuery();
		$resultset = $data->getFilteredData($client, $query, $startPos);
		$highlighting = $resultset->getHighlighting();

		$tables = $this->renderDocumentTables($resultset, $highlighting);

		// Get total number of results found
		$resultCount = $resultset->getNumFound();

		if ($resultCount == 0) {
			$suggestion = $this->getSpellCheck($query);
			if ($suggestion) {
				$suggestion = $suggestion['word'];
			}
		}

		$keywords = SearchFieldEntity::getFields();
		$operators = SolrOperators::getOperators();

		return View::make('results', compact('tables','resultCount','startPos','keywords','operators','suggestion'));
	}
	
	/**
	 * Returns the Case page with similar cases
	 * @return View case
	 */
	public function getCase()
	{
		// Get the case ID
		$caseID = Input::get('id');
		// Get the keywords used to compare other cases with this case
		$similarKeywords = Input::get('keywords');

		$client = $this->getSolrClient();
		
		// Parse form data from HTTP POST action
		$caseData = new SolrQuery();
		$result = $caseData->getCaseData($client, $caseID);
		$doc = $this->renderSingleDocument($result);
		$tables = '';

		$resultCount = 0;
		foreach ($result as $document) {
			$id = $document->id;
			$similarCases = $this->findSimilarCases($id, $similarKeywords);

			$tables .= $this->renderDocumentTables($similarCases, NULL);

			$resultCount = $similarCases->getNumFound();
		}

		$fields = SearchFieldEntity::getFields();

		return View::make('case', compact('doc', 'tables', 'resultCount', 'startPos'));
	}

	/**
	 * Returns the Results page of cases with a given hashtag
	 * @return View results
	 */
	public function getCasesByHashtag()
	{
		$hashtag = Input::get('hashtag');
		$hashtag = urldecode($hashtag);

		$startPos = Input::get('start');
		
		$client = $this->getSolrClient();

		$data = new SolrQuery();
		$resultset = $data->getHashtagCases($client, $hashtag, $startPos);
		$highlighting = $resultset->getHighlighting();

		$tables = $this->renderDocumentTables($resultset, $highlighting);

		$resultCount = $resultset->getNumFound();

		$keywords = SearchFieldEntity::getFields();
		$operators = SolrOperators::getOperators();

		return View::make('results', compact('hashtag', 'tables','resultCount','startPos','keywords','operators'));
	}

	/**
	 * Adds hashtags to a specified case
	 * @return string $caseID the case ID affected
	 */
	public function postAddHashtags()
	{
		$caseID = preg_replace("/[^0-9]/", "", Input::get('caseID'));
		$hashtags = trim(strtolower(Input::get('hashtags')));

		$client = $this->getSolrClient();

		$query = new SolrQuery();
		$query->addHashtags($client, $caseID, $hashtags);

		return $caseID;
	}

	/**
	 * Returns the hashtags of a specified case
	 * @return array $data the list of hashtags
	 */
	public function getHashtags()
	{
		$caseID = preg_replace("/[^0-9]/", "", Input::get('caseID'));

		$client = $this->getSolrClient();

		$query = new SolrQuery();
		$resultset = $query->getHashtag($client, $caseID);

		foreach ($resultset as $document) {
			$hashtags = $document->tag;
		}

		$data = array(
			'caseID'   => $caseID,
			'hashtags' => $hashtags
		);
		return $data;
	}

	/**
	 * Get spelling suggestions for a given term
	 * @param string $term the term to spell check
	 * @return ResultSet $resultSet a collection of spelling suggestions
	 */
	public function getSpellCheck($term = null)
	{
		if (!empty($term)) {
			$query = $term;
		}
		else {
			$query = Input::get('term');
			$query = urldecode($query);
		}
		
		$client = $this->getSolrClient();

		$data = new SolrQuery();
		$resultset = $data->spellCheck($client, $query);
		return $resultset;
	}

	/**
	 * Get autocomplete suggestions for a given term
	 * @return ResultSet $resultSet a collection of autocomplete suggestions
	 */
	public function getAutocomplete()
	{
		$query = Input::get('term');
		$query = urldecode($query);
		
		$client = $this->getSolrClient();

		$data = new SolrQuery();
		$resultset = $data->suggest($client, $query);
		return $resultset;
	}

	/**
	 * Adds a new bookmark to Solr
	 * @return string $status response
	 */
	public function postAddBookmark()
	{
		// User-supplied name for the saved search
		$bookmarkName = Input::get('bookmarkName');

		// The search URL the user wishes to save
		$URL = Input::get('url');

		date_default_timezone_set('America/New_York');

		// We need to build an array to map the bookmarkName and URL
		// Pass this array into the SolrQuery method to persist it
		$bookmark = array(
			'name' => $bookmarkName,
			'url' => $URL,
			'timestamp' => date('m/d/Y h:i:s A'),
			'GUID' => uniqid(time())
			);

		// Get a Solr client
		$client = $this->getSolrClient();

		$query = new SolrQuery();
		$query->addBookmark($client, $bookmark);

		$status = 'success';
		return $status;
	}
	
	/**
	 * Returns the Saved Bookmarks page
	 * @return View saved
	 */
	public function getSavedSearches()
	{
		$client = $this->getSolrClient();

		$query = new SolrQuery();
		$resultset = $query->getBookmarks($client);

		$bookmarks = array();
		foreach ($resultset as $bookmark) {
			 array_push($bookmarks, $bookmark->savedSearches);
		}
		return View::make('saved', compact('bookmarks'));
	}

	/**
	 * Returns similar cases compared to a source case
	 * @param string $caseID the case identifier
	 * @param array $keywords list of keywords to compare on
	 * @return ResultSet $results collection of case documents
	 */
	private function findSimilarCases($caseID, $keywords)
	{
		$client = $this->getSolrClient();
	
		$data = new SolrQuery();
		$results = $data->getSimilarCases($client, $caseID, $keywords);

		return $results;
	}

	/**
	 * Renders a case document to an HTML table output
	 * @param ResultSet $resultset collection of documents
	 * @return string $results the HTML table
	 */
	private function renderSingleDocument($resultset)
	{
		$results = '';
		foreach ($resultset as $document) {
		
		    $results .= '<div id="'. $document->id .'" class="full-doc"><table class="table">';

		    foreach($document AS $field => $value)
		    {
		    	if (is_array($value)) $value = implode(', ', $value);
			   	$results = $results .'<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
		    }
		    $results = $results . '</table></div><br>';
		}
		return $results;
	}

	/**
	 * Renders multiple case documents to an HTML table output
	 * @param ResultSet $resultset collection of documents
	 * @param ResultSet $highlighting collection of matched keywords
	 * @return string $results the HTML table
	 */
	private function renderDocumentTables($resultset, $highlighting)
	{
		$results = '';
		foreach ($resultset as $document) {

			$results .= '<div id="res-' . $document->id .'"class="result-snippet shadow" style="background-color:gray; width:250px; padding:10px;"><div style="text-align:right"><span style="color: #fff; float:left; font-size:12px; font-weight:bold; text-decoration:underline;">' . $document->title[0] .'</span></div>';

		    if (isset($highlighting)) {
		    	$highlightedDoc = $highlighting->getResult($document->id);
		    
				if ($highlightedDoc) {
					$results .= '<div style="padding: 12px;"><table class="table table-condensed">';

			    	foreach($highlightedDoc as $key => $val) {
			    		$results .= '<tr><td style="border-top: 0;"><span style="color: #fff; font-size:12px;">' . $key . '</span></td><td style="border-top: 0;"><strong><span style="color: #fff; font-size:12px;">' . $val[0] . '</span></strong></td></tr>';
			        }
			        $results .= '</table></div>';
			    }
			}
			$results .= '<div id="'. $document->id .'" class="full-doc" style="color: #fff; display: none;"><table class="table" style="padding: 4px; margin: 4px;">';

		    foreach($document AS $field => $value)
		    {
		    	// Converts multi-valued fields to a comma separated string
		        if (is_array($value)) $value = implode(', ', $value);
		        
		        if ($field != 'tag') {
					$results = $results .'<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
				}
		    }
		    $results .= '</table></div>';
		    $results .= '<div><br><button id="' . $document->id . '"class="show btn btn-inverse" type="button">View Document</button>';
			$results .= '<a href="#" id="add-tag-' . $document->id . '"class=" add-hashtag btn btn-inverse btn-small" style="float:right; margin-top:-30px;" data-toggle="popover"><i class="icon-tag icon-white"></i> Tags</a></div>';
		    $results .= '</div><br>';
		}
		return $results;
	}

	/**
	 * Returns a configured Solr client instance
	 * @return Client $client Solr client
	 */
	private function getSolrClient()
	{
		$config = array(
    		'endpoint' => array(
        		'localhost' => array(
            		'host' => 'eclipse67.campus.jcu.edu', // Make sure host is set to eclipse67.campus.jcu.edu
            		'port' => 8983,
            		'path' => '/solr/',
       			)
    		)
		);
		$client = new Client($config);
		return $client;
	}

	/**
	 * Converts a ResultSet to a JSON object
	 * @param ResultSet $resultset collection of documents
	 * @return string $results JSON object
	 */
	private function docArrayToJSON($resultset)
	{
		$results = array();
		foreach($resultset as $document) {
			$item = array();
			foreach($document as $field => $value) {
				$item[$field] = $value;
			}
			$results[] = $item;
		}
		return json_encode($results);
	}
}
