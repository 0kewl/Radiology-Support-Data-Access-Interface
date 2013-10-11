<?php

use Solarium\Client;

class SolrController extends BaseController {

	public function getIndex()
	{

		$keywords = SearchFieldEntity::getFields();
		$operators = array(
			'OR' => 'OR',
			'AND' => 'AND',
			'NOT' => 'NOT'
		);

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
		$resultset = $data->getAllData($client);

		// render html results
		$html = '';

		// show documents using the resultset iterator
		foreach ($resultset as $document) {
		    $html = $html . '<hr/><table>';

		    // the documents are also iterable, to get all fields
		    foreach($document AS $field => $value)
		    {
		        // this converts multivalue fields to a comma-separated string
		        if(is_array($value)) $value = implode(', ', $value);

		        $html = $html . '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
		    }
		    $html = $html . '</table>';
		}

		// keywords and operators for the drop-down elements
		$keywords = SearchFieldEntity::getFields();
		$operators = array(
			'OR' => 'OR',
			'AND' => 'AND',
			'NOT' => 'NOT'
		);
		
		return View::make('results', compact('response','html','keywords','operators'));
	}

	private function getSolrClient()
	{
		$config = array(
    		'endpoint' => array(
        		'localhost' => array(
            		'host' => '127.0.0.1',
            		'port' => 8983,
            		'path' => '/solr/',
       			)
    		)
		);

		// create a Solr client instance
		$client = new Client($config);

		return $client;
	}
	
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
