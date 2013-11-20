<?php

use Solarium\Client;

class SolrQuery {

	/**
	 * Returns all cases in the Solr database
	 * @param Client $client configured Solr client
	 * @return ResultSet $resultset collection of documents
	 */
	public function getAllData($client)
	{
		// Select query instance
		$query = $client->createSelect();

		// No query parameters supplied, so we get all results
		$resultset = $client->select($query);
		return $resultset;
	}

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
		$hl->setSimplePrefix('<u>');
		$hl->setSimplePostfix('</u>');

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
		$dismax->setQueryFields("tag");

		$query->setRows(10);

		$dismax->setQueryParser('edismax');
		$query->setQuery($q);

		// Get highlighting component and apply settings
		$hl = $query->getHighlighting();
		$hl->setFields(array_keys(SearchFieldEntity::getFields()));

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
	public function getSimilarCases($client, $id, $keywords)
	{
		// Get a morelikethis query instance
		$query = $client->createMoreLikeThis();

		// Set the seed document
		$query->setQuery('id:' . $id);

		// Set the fields to use for similarity
		$query->setMltFields($keywords);
		$query->setMinimumDocumentFrequency(1);
		$query->setMinimumTermFrequency(1);

		$query->setRows(50);
		$query->setMatchInclude(true);

		$resultset = $client->select($query);
		return $resultset;
	}

	/**
	 * Adds user-supplied hashtags to a case document
	 * @param Client $client configured Solr client
	 * @param string $id the case identifier
	 * @param array $newHashtags added hashtags
	 * @return void
	 */
	public function addHashtags($client, $id, $newHashtags)
	{
		// We need to get the current hashtags of the case
		$result = $this->getCaseData($client, $id);
		$currentTags = new stdClass();

		// Get the tag property
		foreach ($result as $document) {
			$currentTags = $document->tag;
		}

		// The list of both new and existing hashtags
		$updatedHashtags = array();

		// Existing hashtags for this case
		if (!empty($currentTags)) {
			foreach ($currentTags as $t) {
				array_push($updatedHashtags, $t);
			}
		}
		// Convert the list of new hashtags to an array
		$updatedHashtags = array_merge(explode(',', $newHashtags), $updatedHashtags);

		$update = $client->createUpdate();
		$doc= $update->createDocument();

		$doc->setKey('id', $id);              
	    $doc->setField('tag', $updatedHashtags);
	    $doc->setFieldModifier('tag', 'set');

		$update->addDocument($doc);
		$update->addCommit();

		$result = $client->update($update);
	}

	/**
	 * Returns a list of hashtags associated with a case
	 * @param Client $client configured Solr client
	 * @param string $id the case identifier
	 * @return ResultSet $resultset collection of documents
	 */
	public function getHashtag($client, $id)
	{
		// Select query instance
		$query = $client->createSelect();
		
		// Just search on the ID field
		$query->setQuery("id:" . $id);
		$query->setFields(array('id','tag'));
		
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

	/**
	 * Adds a new bookmarked search to Solr
	 * @param Client $client configured Solr client
	 * @param string $bookmark bookmark query
	 * @return void
	 */
	public function addBookmark($client, $bookmark)
	{
		$result = $this->getCaseData($client, 'RAD-bookmarks');
		$currentTags = new stdClass();

		// Get the savedSearches field values
		foreach ($result as $document) {
			$currentTags = $document->savedSearches;
		}
		$updatedBookmarks = array();

		if (!empty($currentBookmarks)) {
			foreach ($currentBookmarks as $t) {
				array_push($updatedBookmarks, $t);
			}
		}

		$updatedBookmarks = array_merge($bookmark, $updatedBookmarks);

		$update = $client->createUpdate();
		$doc= $update->createDocument();

		// The key for all bookmarks is 'RAD-bookmarks'
		// Short for 'radiology bookmarks'
	    $doc->setKey('id', 'RAD-bookmarks');
	    $doc->setField('savedSearches', $updatedBookmarks);
	    $doc->setFieldModifier('tag', 'set'); 

		$update->addDocument($doc);
		$update->addCommit();

		$result = $client->update($update);
	}
	
	/**
	 * Returns all saved bookmarks in Solr
	 * @param Client $client configured Solr client
	 * @return ResultSet $resultset collection of documents
	 */
	public function getBookmarks($client)
	{
		// Select query instance
		$query = $client->createSelect();
		$query->setQuery("id: RAD-bookmarks");
		$query->setFields(array('savedSearches'));
		
		$resultset = $client->select($query);
		return $resultset;
	}
}
