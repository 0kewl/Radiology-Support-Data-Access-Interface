<?php

use Solarium\Client;

class SolrController extends BaseController {

	// Handle search page GET request
	public function getIndex()
	{
		$keywords = SearchFieldEntity::getFields();
		$operators = SolrOperators::getOperators();

		return View::make('index', compact('keywords', 'operators'));
	}

	// Handle search page POST request
	// Process the user search query and display results page
	public function postResults()
	{
		// Get the form input values
		$mainQuery = Input::get('main-query');
		$keywords =  json_decode(Input::get('json'));

		$response = new SolrResponse();

		$response->query = $mainQuery;
		$response->keywords = $keywords;

		// Get a Solr client
		$client = $this->getSolrClient();

		// Parse the form data from the HTTP POST action
		$data = new SolrQuery();
		$resultset = $data->getFilteredData($client, $response);
		$highlighting = $resultset->getHighlighting();

		$tables = $this->renderDocumentTables($resultset, $highlighting);

		// How many results did we find?
		$resultCount = $resultset->getNumFound();

		// Keywords and operators for the drop-down elements
		$keywords = SearchFieldEntity::getFields();
		$operators = SolrOperators::getOperators();

		return View::make('results', compact('response','tables','resultCount','keywords','operators'));
	}
	
	// Attempt to find a case by its ID and display the case page
	public function postCaseLookup()
	{
		$caseID = Input::get('case-id');
		$similarKeywords = Input::get('similar-keywords');

		// Get a Solr client
		$client = $this->getSolrClient();
		
		// Parse form data from HTTP POST action
		$caseData = new SolrQuery();
		$result = $caseData->getCaseData($client, $caseID);
		$doc = $this->renderSingleDocument($result);
		$tables = '';

		foreach ($result as $document) {
			$id = $document->id;
			$similarCases = $this->findSimilarCases($id, $similarKeywords);

			$tables .= $this->renderDocumentTables($similarCases, NULL);
		}

		$fields = SearchFieldEntity::getFields();

		// How many results did we find?
		$resultCount = $result->getNumFound();

		return View::make('case', compact('doc', 'tables', 'resultCount'));
	}

	private function findSimilarCases($caseID, $keywords)
	{
		// Get a Solr client
		$client = $this->getSolrClient();
		
		// Parse form data from HTTP POST action
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

			$results .= '<div id="res-' . $document->id .'"class="result-snippet shadow" style="background-color:gray; width: 265px; padding: 10px;"><div style="text-align:right"><span style="color:#fff; float:left; font-size:12px; font-weight:bold; text-decoration:underline;">' . $document->title[0] .'</span></div>';
			
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
			$results .= '<button id="' . $document->id . '"class="show btn btn-inverse" type="button">View Document</button>';
			$results .= '<button id="add-tag-' . $document->id . '"class="show btn btn-inverse btn-small" type="button" style="float:right; margin-top:-30px;"><i class="icon-tag icon-white"></i> Tag</button>';
			$results .= '<div id="'. $document->id .'" class="full-doc" style="color: #fff; display: none;"><table class="table" style="padding: 4px; margin: 4px;">';
			
		    foreach($document AS $field => $value)
		    {
		       // Converts multi-valued fields to a comma separated string
		       if (is_array($value)) $value = implode(', ', $value);

			   $results = $results .'<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
		    }
		    
		    $results = $results . '</table></div></div><br>';
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
