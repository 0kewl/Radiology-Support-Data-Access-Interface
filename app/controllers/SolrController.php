<?php

use Solarium\Client;

class SolrController extends BaseController {

	public function getIndex()
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

		// check solarium version available
		echo 'Solarium library version: ' . Client::VERSION . ' - ';

		// create a client instance
		$client = new Client($config);
		
		// get a select query instance
		$query = $client->createQuery($client::QUERY_SELECT);
		
		// this executes the query and returns the result
		$resultset = $client->execute($query);
		
		// display the total number of documents found by solr
		echo 'NumFound: '.$resultset->getNumFound();
		
		$resultset = $client->select($query);
		
		$results = $this->docArrayToJSON($resultset);
		
		
		return View::make('search', compact('results'));
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

