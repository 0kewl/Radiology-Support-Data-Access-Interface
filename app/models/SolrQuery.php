<?php

use Solarium\Client;

class SolrQuery {

	public function getAllData($client) {

		// get a select query instance
		$query = $client->createSelect();

		$resultset = $client->select($query);
		return $resultset;
	}

	public function getFilteredData($client, $userQuery) {

		// get a select query instance
		$query = $client->createSelect();

		$parameters = $userQuery->query;
		foreach ($userQuery->keywords as $element) {
    		$parameters = $parameters . " " . $element->operator . " " . $element->field . ":" . $element->keyword;
		}

		// get the dismax component and set a boost query
		$dismax = $query->getDisMax();
		$dismax->setQueryFields(array_keys(SearchFieldEntity::getFields()));

		// boost query, we might enable this feature in the future
		// $dismax->setBoostQuery('');

		// override the default setting of 'dismax' to enable 'edismax'
		$dismax->setQueryParser('edismax');

		// this query is now a dismax query
		$query->setQuery($parameters);

		// get highlighting component and apply settings
		$hl = $query->getHighlighting();
		$hl->setFields(array_keys(SearchFieldEntity::getFields()));
		$hl->setSimplePrefix('<b>');
		$hl->setSimplePostfix('</b>');

		$resultset = $client->select($query);

		return $resultset;
	}
}
