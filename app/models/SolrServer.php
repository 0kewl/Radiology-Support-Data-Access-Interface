<?php

use Solarium\Client;

class SolrServer extends BaseController {

public function getSolrClient() {

		$config = array(
    		'endpoint' => array(
        		'localhost' => array(
            		'host' => 'eclipse67.campus.jcu.edu',
            		'port' => 8983,
            		'path' => '/solr/',
       			)
    		)
		);
		$client = new Client($config);
		return $client;
	}
}