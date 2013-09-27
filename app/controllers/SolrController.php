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
		$client = new Solarium\Client($config);

		// create a ping query
		$ping = $client->createPing();

		// execute the ping query
		try{
		    $result = $client->ping($ping);
		    echo 'Ping query successful';
		    echo '<br/><pre>';
		    var_dump($result->getData());
		}catch(Exception $e){
		    echo 'Ping query failed';
		}
		return View::make('search');
	}

}