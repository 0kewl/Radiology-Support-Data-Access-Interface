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
		$modified = false;

		// Get the cursor starting position
		$startPos = Input::get('start');

		if ($query == "*") {
			$query = "-bookmarkName:*";
			$modified = true;
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

		if ($modified) {
			$query = "All cases";
		}

		$keywords = SearchFieldEntity::getFields();
		$operators = SolrOperators::getOperators();

		return View::make('results', compact('query','tables','resultCount','startPos','keywords','operators','suggestion'));
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

		return View::make('case', compact('doc', 'tables', 'resultCount', 'startPos','caseID'));
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

		//$cases = CaseHashtagsQuery::select('case_id')->where('tag', '=', $hashtag)->get();
		$cases = new CaseHashtagsQuery();
		$cases = $cases->getCasesByTag($hashtag);

		$client = $this->getSolrClient();

		$data = new SolrQuery();
		$resultset = $data->getHashtagCases($client, $cases, $startPos);
		$highlighting = $resultset->getHighlighting();

		$tables = $this->renderDocumentTables($resultset, $highlighting);

		$resultCount = $resultset->getNumFound();

		$keywords = SearchFieldEntity::getFields();
		$operators = SolrOperators::getOperators();

		return View::make('results', compact('hashtag', 'tables','resultCount','startPos','keywords','operators'));
	}

	/**
	 * Adds hashtags to a specified case
	 * @return string caseID modified
	 */
	public function postAddHashtags()
	{
		$caseID = preg_replace("/[^0-9]/", "", Input::get('caseID'));
		$hashtags = explode(",", trim(strtolower(Input::get('hashtags'))));

		foreach ($hashtags as $h) {

			try {
				$found = HashtagsQuery::where('tag', '=', $h)->first();

				if (!$found) {
					$hashtagCollection = new HashtagsQuery();

					$hashtagCollection->tag = trim(strtolower($h));
					$hashtagCollection->save();
					$found = $hashtagCollection;
				}

				$caseHashtag = new CaseHashtagsQuery();

				$caseHashtag->case_id = $caseID;
				$caseHashtag->hashtag_id = $found->id;
				$caseHashtag->save();
			}
			// Console only threw a generic Exception

			catch(Exception $e) {
				$error = new StdClass();

				$error->error = true;
				$error->message = "Hashtag already exists for this case.";
				return json_encode($error);
			}
		}
		return $caseID;
	}

	/**
	 * Deletes a given hashtag from a case ID
	 * @return string $caseID
	 */
	public function postDeleteHashtag()
	{
		$caseID = Input::get('caseID');
		$hashtag = trim(Input::get('hashtag'));

		$tagModel = HashtagsQuery::where('tag', '=', $hashtag)->first();

		$toDeleteHashtag = new CaseHashtagsQuery();
		$toDeleteHashtag = $toDeleteHashtag->findCaseTag($caseID, $tagModel->id);

		$toDeleteHashtag->delete();

		$numHashtagReferences = CaseHashtagsQuery::where('hashtag_id','=', $tagModel->id)->count();

		// Are there any more references to the saved hashtag, if not, delete it from the database
		if ($numHashtagReferences <= 0) {
			$oldTag = HashtagsQuery::where('id','=', $tagModel->id)->first();
			$oldTag->delete();
		}

		return $caseID;
	}

	/**
	 * Returns the hashtags of a specified case
	 * @return array $data the list of hashtags
	 */
	public function getHashtags()
	{
		$caseID = preg_replace("/[^0-9]/", "", Input::get('caseID'));

		$hashtags = new HashtagsQuery();
		$hashtags = $hashtags->getCaseTags($caseID);

		return $hashtags;
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
	 * Get suggestions for a given hashtag
	 * @return ResultSet $resultSet a collection of autocomplete suggestions
	 */
	public function getHashtagSuggestions()
	{
		$query = Input::get('term');

		$collection = new HashtagsQuery();
		$collection = $collection->findHashtags($query);

		return $collection;
	}

	/**
	 * Returns the Saved Bookmarks page
	 * @return View saved
	 */
	public function getSavedSearches()
	{
		$bookmarks = BookmarksQuery::paginate(10);
		$count = BookmarksQuery::count();
		return View::make('saved', compact('bookmarks', 'count'));
	}

	/**
	 * Returns the Saved Hashtags page
	 * @return View saved
	 */
	public function getSavedHashtags()
	{
		$hashtags = HashtagsQuery::select('tag')->paginate(10);
		$count = HashtagsQuery::count();
		return View::make('savedhashtags', compact('hashtags', 'count'));
	}

	/**
	 * Adds a new bookmark to the database
	 * @return void
	 */
	public function postAddBookmark()
	{
		// User-supplied name for the saved search
		$bookmarkName = trim(Input::get('bookmarkName'));

		// The search URL the user wishes to save
		$URL = Input::get('url');
		date_default_timezone_set('America/New_York');

		$bookmark = new BookmarksQuery();

		$bookmark->name = trim($bookmarkName);
		$bookmark->url = $URL;
		$bookmark->timestamp = date('m/d/Y h:i:s A');

		$bookmark->save();
	}

	/**
	 * Deletes a bookmark from the database
	 * @return void
	 */
	public function postDeleteBookmark()
	{
		$bookmarkID = Input::get('bookmarkID');

		$bookmark = BookmarksQuery::find($bookmarkID);
		$bookmark->delete();
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

			$results .= '<div id="res-' . $document->id .'"class="result-snippet shadow" style="background-color:gray; width:250px; height:auto; padding:10px;">';

			$results .= '<div id="title-container" style="overflow-x:auto;">';

				$results .= '<div style="height: 40px;"><span style="float:right;"><a href="#" style="color:#fff";" class="edit-hashtag" doc="' . $document->id . '">';
				$results .= '<i class="icon-tag" style="color:#F88017"></i><i class="icon-tag icon-minus-sign" style="color:white"></i></a></span>';

				$results .= '<span style="color: #fff; float:left; font-size:12px; font-weight:bold; text-decoration:underline;">' . $document->title[0] .'</span></div>';

			$results .= '</div>';
			
			$results .= '<div id="table-container"><table class="table table-condensed">';

		    if (isset($highlighting)) {
		    	$highlightedDoc = $highlighting->getResult($document->id);
		    
				if ($highlightedDoc) {

			    	foreach($highlightedDoc as $key => $val) {
			    		$results .= '<tr><td style="border-top: 0;"><span style="color: #fff; font-size:12px;">' . $key . ':</span></td><td style="border-top: 0;"><strong><span style="color: #fff; font-size:12px;">' . $val[0] . '</span></strong></td></tr>';
			        }
			    }
			}
			$results .= '</table></div>';

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
		    $results .= '<div id="case-buttons"><br><div style="float:left; margin-top: -28px;"><button id="' . $document->id . '"class="show btn btn-inverse" type="button"><i class="icon-file"></i> <span style="font-size:12px";>View Case</span></button></div>';
			$results .= '<div style="margin-top: -28px;"><a href="your-target-image-url:=' . $document->id . '" target="_blank"><button class="btn btn-inverse" style="margin-left: 7px;"><i class="icon-picture"></i></button></a>';
			$results .= '<a href="#" id="add-tag-' . $document->id . '"class="add-hashtag btn btn-inverse btn-small" style="margin-left: 34px;" data-toggle="popover"><i class="icon-tag" style="color:#F88017"></i> Tags</a></div></div>';
		    $results .= '</div><br>';
		}
		return $results;
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

	/**
	 * Returns a configured Solr client instance
	 * @return Client $client Solr client
	 */
	private function getSolrClient()
	{
		$client = new SolrServer();
		$client = $client->getSolrClient();
		return $client;
	}
}
