<?php

use Solarium\Client;

class SolrController extends BaseController {

	public function getIndex()
	{
		$keywords = SearchFieldEntity::getFields();
		$operators = SolrOperators::getOperators();

		return View::make('index', compact('keywords', 'operators'));
	}

	// Handle a search
	public function getResults()
	{
		$query = Input::get('q');
		$query = urldecode($query);

		$startPos = Input::get('start');
		
		// Get a Solr client
		$client = $this->getSolrClient();

		$data = new SolrQuery();
		$resultset = $data->getFilteredData($client, $query, $startPos);
		$highlighting = $resultset->getHighlighting();

		$tables = $this->renderDocumentTables($resultset, $highlighting);

		// How many results did we retrieve?
		$resultCount = $resultset->getNumFound();

		if ($resultCount == 0) {
			$suggestion = $this->getSpellCheck($query);
			if ($suggestion) {
				$suggestion = $suggestion['word'];
			}
		}

		// Keywords and operators for the select elements
		$keywords = SearchFieldEntity::getFields();
		$operators = SolrOperators::getOperators();

		return View::make('results', compact('tables','resultCount','startPos','keywords','operators','suggestion'));
	}
	
	// Find a case by its ID
	public function getCase()
	{
		$caseID = Input::get('id');
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

			// How many results did we find?
			$resultCount = $similarCases->getNumFound();
		}

		$fields = SearchFieldEntity::getFields();

		return View::make('case', compact('doc', 'tables', 'resultCount', 'startPos'));
	}

	// Return cases based on a given hashtag
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

		// How many results did we retrieve
		$resultCount = $resultset->getNumFound();

		// Keywords and operators for the select elements
		$keywords = SearchFieldEntity::getFields();
		$operators = SolrOperators::getOperators();

		return View::make('results', compact('hashtag', 'tables','resultCount','startPos','keywords','operators'));
	}

	// Add hashtags to a case
	public function postAddHashtags()
	{
		$caseID = preg_replace("/[^0-9]/", "", Input::get('caseID'));
		$hashtags = strtolower(Input::get('hashtags'));

		$client = $this->getSolrClient();

		$query = new SolrQuery();
		$query->addHashtag($client, $caseID, $hashtags);
		// Let's be friendly and return the case id we modified
		return $caseID;
	}


	// Get hashtags of a case
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

	public function getAutocomplete()
	{
		$query = Input::get('term');
		$query = urldecode($query);
		
		$client = $this->getSolrClient();

		$data = new SolrQuery();
		$resultset = $data->suggest($client, $query);
		return $resultset;
	}
	
	// Add a bookmark
	public function postAddBookmark()
	{
		$bookmark = strtolower(Input::get('bookmark'));

		// Get a Solr client
		$client = $this->getSolrClient();

		$query = new SolrQuery();
		$query->addBookmark($url, $bookmark);
		// Let's be friendly and return the case id we modified
		return $url;
	}
	
	//Get a bookmark
	public function getSavedSearches()
	{
		// Get a Solr client
		$client = $this->getSolrClient();

		$query = new SolrQuery();
		$resultset = $query->getBookmark($url);

		foreach ($resultset as $document) {
			$bookmark = $document->savedSearches;
		}

		$data = array(
			'url'   => $url,
			'bookmark' => $bookmark
		);
		return $data;
	
		//return 'saved search page';
		//return View::make('saved', compact('hashtag', 'tables','resultCount','startPos','keywords','operators'));
	}

	private function findSimilarCases($caseID, $keywords)
	{
		$client = $this->getSolrClient();
	
		$data = new SolrQuery();
		$results = $data->getSimilarCases($client, $caseID, $keywords);

		return $results;
	}

	// Renders a single Solr document to an HTML table
	private function renderSingleDocument($resultset)
	{
		$results = '';

		// show documents using the resultset iterator
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

	// Renders a Solr dataset to an HTML table
	private function renderDocumentTables($resultset, $highlighting)
	{
		$results = '';

		// Show documents using the resultset iterator
		foreach ($resultset as $document) {

			$results .= '<div id="res-' . $document->id .'"class="result-snippet shadow" style="background-color:gray; width: 246px; padding: 10px; margin-right: 20px;"><div style="text-align:right"><span style="color:#fff; float:left; font-size:12px; font-weight:bold; text-decoration:underline;">' . $document->title[0] .'</span></div>';
			
			// Highlighting results can be fetched by document id (the field defined as the unique key in this schema)
		    if (isset($highlighting)) {
		    	$highlightedDoc = $highlighting->getResult($document->id);
		    
				if ($highlightedDoc) {
					$results .= '<div style="padding: 12px;"><table class="table table-condensed">';

			    	foreach($highlightedDoc as $key => $val) {
			    		$results .= '<tr><td style="border-top: 0;"><span style="color: #fff;">' . $key . '</span></td><td style="border-top: 0;"><strong><span style="color: #fff;">' . $val[0] . '</span></strong></td></tr>';
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

	// Returns a configured Solr client
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

		// Create a Solr client instance
			$client = new Client($config);

			return $client;
	}

	// Helper method to convert a result set to a JSON object
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
