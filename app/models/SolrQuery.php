<?php

use Solarium\Client;

class SolrQuery {

	/**
	 * Returns a filtered list of documents from Solr
	 * @param Client $client configured Solr client
	 * @param string $q search query
	 * @param int $startPos the cursor starting position
	 * @return ResultSet $resultset collection of documents
	 */
	public function getFilteredData($client, $q, $startPos)
	{
		$query = $client->createSelect();

		 // Disjunction refers to the fact that your search is executed across
		 // multiple fields with different relevance weights.
		$dismax = $query->getDisMax();
		$dismax->setQueryFields(array_keys(SearchFieldEntity::getFields()));

		$query->setRows(10);

		$dismax->setQueryParser('edismax');
		$query->setQuery($q);

		// Get highlighting component which marks matched keywords
		$hl = $query->getHighlighting();
		$hl->setFields(array_keys(SearchFieldEntity::getFields()));
		$hl->setSimplePrefix('<b><u><span style="font-size:16px;">');
		$hl->setSimplePostfix('</span></u></b>');

		// Handle pagination
		$start = 0;
		if (!empty($startPos)) {
			$start = $startPos;
			$query->setStart($start);
		}

		$resultset = $client->select($query);
		return $resultset;
	}

	/**
	 * Returns a specific case document
	 * @param Client $client configured Solr client
	 * @param string $id the case identifier
	 * @return ResultSet $resultset collection of documents
	 */
	public function getCaseData($client, $id)
	{
		// Select query instance
		$query = $client->createSelect();
		
		// Just search on the ID field
		$query->setQuery("id:" . $id);
		
		$resultset = $client->select($query);
		return $resultset;
	}

	/**
	 * Returns a list of hashtags based on a specified query
	 * @param Client $client configured Solr client
	 * @param string $q hashtag search query
	 * @param int $startPos the cursor starting position
	 * @return ResultSet $resultset collection of documents
	 */
	public function getHashtagCases($client, $q, $startPos)
	{
		// Select query instance
		$query = $client->createSelect();

		$dismax = $query->getDisMax();
		$dismax->setQueryFields("id");

		$query->setRows(10);

		$dismax->setQueryParser('edismax');
		$query->setQuery($q);

		// Get highlighting component and apply settings
		$hl = $query->getHighlighting();
		$hl->setFields('id');

		// We want to bold matching results
		$hl->setSimplePrefix('<u>');
		$hl->setSimplePostfix('</u>');

		// Handle paginating
		$start = 0;
		if (!empty($startPos)) {
			$start = $startPos;
			$query->setStart($start);
		}

		$resultset = $client->select($query);
		return $resultset;
	}

	/**
	 * Returns a list of cases similar to the source case
	 * and relevant keywords
	 * @param Client $client configured Solr client
	 * @param string $id the case identifier
	 * @param string $keywords list of relevant keywords
	 * @return ResultSet $resultset collection of documents
	 */
	public function getSimilarCases($client, $id, $keywords, $count)
	{
		// Get a morelikethis query instance
		$query = $client->createMoreLikeThis();

		// Set the seed document
		$query->setQuery('id:' . $id);

		// Set the fields to use for similarity
		$query->setMltFields($keywords);
		$query->setMinimumDocumentFrequency(1);
		$query->setMinimumTermFrequency(1);

		$query->setRows((int)$count);
		$query->setMatchInclude(true);

		$resultset = $client->select($query);
		return $resultset;
	}

	/**
	 * Performs a spell check on a search query and
	 * suggests spelling corrections
	 * @param Client $client configured Solr client
	 * @param string $q search query
	 * @return array $correction spelling corrections
	 */
	public function spellCheck($client, $q)
	{
		// Get a select query instance
		$query = $client->createSelect();
		$query->setRows(0);

		// Add spellcheck settings
		$spellcheck = $query->getSpellcheck();
		$spellcheck->setQuery($q);
		$spellcheck->setCount(1);
		$spellcheck->setBuild(true);
		$spellcheck->setCollate(true);
		$spellcheck->setExtendedResults(true);
		$spellcheck->setCollateExtendedResults(true);

		// Execute the query and return the results
		$resultset = $client->select($query);
		$spellcheckResult = $resultset->getSpellcheck();

		if (isset($spellcheckResult)) {
			if ($spellcheckResult->getCorrectlySpelled()) {
			    //echo 'yes';
			}
			else {
			    //echo 'no';
			}
		}

		$correction = '';
		if (!empty($spellcheckResult)) {
			foreach($spellcheckResult as $suggestion) {
			    foreach ($suggestion->getWords() as $word) {
			    	$correction = $word;
			    }
			}
		}
		return $correction;
	}

	/**
	 * Returns a list of autocomplete suggestions based
	 * on a fragment of a search query
	 * @param Client $client configured Solr client
	 * @param string $q search query
	 * @return array $suggestions autocomplete suggestions
	 */
	public function suggest($client, $q)
	{
		// Get a suggester query instance
		$query = $client->createSuggester();
		$query->setQuery($q);
		$query->setDictionary('suggest');
		$query->setOnlyMorePopular(true);
		$query->setCount(10);
		$query->setCollate(true);

		$resultset = $client->suggester($query);

		// Display results for each suggested term
		$suggestions = array();

		foreach ($resultset as $term => $termResult) {
		    foreach($termResult as $result) {
		       $element = array(
					"key" => $result,
					"value" => $result
				);
				array_push($suggestions, $element);
		    }
		}
		return $suggestions;
	}
}
