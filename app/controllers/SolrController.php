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
		
		    $results .= '<div id="'. $document->id .'" class="full-doc"><table class="table table-striped">';

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

			$results .= '<div id="res-' . $document->id .'"class="result-snippet box" style=" width: 265px; padding: 12px;"><div style="text-align:right"><i class="icon-star2"></i></div>';

		    // Highlighting results can be fetched by document id (the field defined as the unique key in this schema)
		    if (isset($highlighting)) {
		    	$highlightedDoc = $highlighting->getResult($document->id);
		    
				if ($highlightedDoc) {
					$results .= '<div style="padding: 12px;"><table class="table table-condensed">';

			    	foreach($highlightedDoc as $key => $val) {
			    		$results .= '<tr><td style="border-top: 0;">' . $key . '</td><td style="border-top: 0;"><strong> ' . $val[0] . '</strong></td></tr>';
			        }
			        $results .= '</table></div>';
			    }
			}

		    $results .= '<button id="' . $document->id . '"class="show btn btn-success" type="button">View Document</button><div id="'. $document->id .'" class="full-doc" style="display: none;"><table class="table table-striped" style="padding: 5px; margin: 5px;">';

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
