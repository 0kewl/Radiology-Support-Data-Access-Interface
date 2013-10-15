<?php

class SolrOperators {

	// Operators used in search Solr search queries
	public static function getOperators() {

		return array(
			'OR'  => 'OR',
			'AND' => 'AND',
			'NOT' => 'NOT'
		);
	}
}
