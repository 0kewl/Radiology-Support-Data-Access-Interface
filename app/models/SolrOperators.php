<?php

class SolrOperators {

	// Operators used in Solr search queries
	public static function getOperators() {

		return array(
			'OR'  => 'OR',
			'AND' => 'AND',
			'NOT' => 'NOT'
		);
	}
}
