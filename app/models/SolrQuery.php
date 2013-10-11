<?php

use Solarium\Client;

class SolrQuery {

	public function getAllData($client) {

		// get a select query instance
		$query = $client->createSelect();

		$resultset = $client->select($query);
		
		return $resultset;
	}

}