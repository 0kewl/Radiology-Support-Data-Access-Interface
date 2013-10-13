<?php

use Solarium\Client;

class SolrController extends BaseController {

	public function getIndex()
	{

		$keywords = SearchFieldEntity::getFields();
		$operators = SolrOperators::getOperators();

		return View::make('index', compact('keywords', 'operators'));
	}

	public function postResults()
	{
		// parse the POSTed form data
		$main_query = Input::get('main-query');
		$keywords =  json_decode(Input::get('json'));

		$response = new SolrResponse();

		$response->query = $main_query;
		$response->keywords = $keywords;

		// get a Solr client
		$client = $this->getSolrClient();

		// parse form data
		$data = new SolrQuery();
		$resultset = $data->getFilteredData($client, $response);

		$highlighting = $resultset->getHighlighting();

		// render html results
		$results = '';

		// show documents using the resultset iterator
		foreach ($resultset as $document) {

			$results = $results . '<div id="res-' . $document->id .'"class="result-snippet box" style=" width: 265px; padding: 12px;"><div style="text-align:right"><i class="icon-star2"></i></div>';

		    // highlighting results can be fetched by document id (the field defined as uniquekey in this schema)
		    $highlightedDoc = $highlighting->getResult($document->id);

			if ($highlightedDoc) {
				$results = $results . '<div style="padding: 12px;"><table class="table table-condensed">';

		    	foreach($highlightedDoc as $key => $val) {
		    		$results = $results . '<tr><td style="border-top: 0;">' . $key . '</td><td style="border-top: 0;"><strong> ' . $val[0] . '</strong></td></tr>';
		        }
		        $results = $results . '</table></div>';
		    }

		    $results = $results . '<button id="' . $document->id . '"class="show btn btn-success" type="button">View Document</button><div id="'. $document->id .'" class="full-doc" style="display: none;"><table class="table table-striped" style="padding: 5px; margin: 5px;">';

		    // the documents are also iterable, to get all fields
		    foreach($document AS $field => $value)
		    {
		       // this converts multivalue fields to a comma-separated string
		       if (is_array($value)) $value = implode(', ', $value);
			   $results = $results .'<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
		    }
		    $results = $results . '</table></div></div><br>';
		}
		$resultCount = $resultset->getNumFound();

		// keywords and operators for the drop-down elements
		$keywords = SearchFieldEntity::getFields();
		$operators = SolrOperators::getOperators();

		return View::make('results', compact('response','results','resultCount','keywords','operators'));
	}

	private function getSolrClient()
	{
		// NOTICE: Please make sure host is set to eclipse67.campus.jcu.edu
		$config = array(
    		'endpoint' => array(
        		'localhost' => array(
            		'host' => 'eclipse67.campus.jcu.edu',
            		'port' => 8983,
            		'path' => '/solr/',
       			)
    		)
		);

		// create a Solr client instance
		$client = new Client($config);

		return $client;
	}
	
	// helper method to convert a result set to a JSON object
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
