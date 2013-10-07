<?php

use Solarium\Client;

class SolrResultSet {

	public function getAllData($client) {

		// get a select query instance
		$query = $client->createSelect();

		$resultset = $client->select($query);
		
		return $resultset;
	}

}