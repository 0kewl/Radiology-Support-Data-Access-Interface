<?php

use Solarium\Client;

class SolrQuery {

	// Get all documents from Solr
	public function getAllData($client) {

		// Select query instance
		$query = $client->createSelect();

		// No query parameters supplied, so we get everything back
		$resultset = $client->select($query);
		return $resultset;
	}

	// Get a filtered result set of documents from Solr
	public function getFilteredData($client, $userQuery) {

		// Select query instance
		$query = $client->createSelect();

		$parameters = $userQuery->query;

		// Build the query string
		foreach ($userQuery->keywords as $element) {
    		$parameters .= " " . $element->operator . " " . $element->field . ":" . $element->keyword;
		}

		// Get the dismax component
		// From Solr Documentation: "Disjunction refers to the fact that your search is executed
		// across multiple fields, e.g. title, body and keywords, with different relevance weights."
		// Read more at http://wiki.apache.org/solr/DisMax
		$dismax = $query->getDisMax();
		$dismax->setQueryFields(array_keys(SearchFieldEntity::getFields()));

		// Set a boost query
		// We might enable this feature in the future...disabled for now
		// $dismax->setBoostQuery('');

		// Override the default setting of 'dismax' to enable 'edismax'
		// From Solr Documentation: "Edismax searches for the query words across multiple fields with
		// different boosts, based on the significance of each field. Additional options let you influence
		// the score based on rules specific to each use case (independent of user input)."
		$dismax->setQueryParser('edismax');

		$query->setQuery($parameters);

		// Get highlighting component and apply settings
		$hl = $query->getHighlighting();
		$hl->setFields(array_keys(SearchFieldEntity::getFields()));

		// We want to bold matching results
		$hl->setSimplePrefix('<b>');
		$hl->setSimplePostfix('</b>');

		$resultset = $client->select($query);
		return $resultset;
	}
	
	// Given a case ID, return the matching document
	public function getCaseData($client, $id)
	{
		// Select query instance
		$query = $client->createSelect();
		
		// Just search on the ID field
		$query->setQuery("id:".$id);
		
		$resultset = $client->select($query);
		return $resultset;
	}
}
