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
	public function getFilteredData($client, $q, $startPos) {

		// Select query instance
		$query = $client->createSelect();

		// Disjunction refers to the fact that your search is executed across multiple fields
		// with different relevance weights.
		$dismax = $query->getDisMax();
		$dismax->setQueryFields(array_keys(SearchFieldEntity::getFields()));

		$query->setRows(10);

		// Set a boost query
		// We might enable this feature in the future
		// $dismax->setBoostQuery('');

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
	
	// Given a case ID, return the matching document
	public function getCaseData($client, $id)
	{
		// Select query instance
		$query = $client->createSelect();
		
		// Just search on the ID field
		$query->setQuery("id:" . $id);
		
		$resultset = $client->select($query);
		return $resultset;
	}

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

		//$query->createFilterQuery('query')->setQuery('inStock:true');
		//$query->setInterestingTerms('details');
		$query->setMatchInclude(true);

		$resultset = $client->select($query);
		return $resultset;
	}

	public function addHashtag($client, $id, $newHashtags)
	{
		// We need to get the current case's tags
		$result = $this->getCaseData($client, $id);
		$currentTags = new stdClass();
		// Get the tag property
		foreach ($result as $document) {
			$currentTags = $document->tag;
		}

		// The list of all hashtags, both new and existing
		$updatedHashtags = array();

		// Tags already existing in Solr
		if (!empty($currentTags)) {
			foreach ($currentTags as $t) {
				array_push($updatedHashtags, $t);
			}
		}
		// Turn the list of new hashtags into an array
		$updatedHashtags = array_merge(explode(',', $newHashtags), $updatedHashtags);

		$update = $client->createUpdate();
		$doc= $update->createDocument();

		$doc->setKey('id', $id);              

	    $doc->setField('tag', $updatedHashtags);
	    $doc->setFieldModifier('tag', 'set');     

		// Add document and commit
		$update->addDocument($doc);
		$update->addCommit();

		// Runs the query and returns the result
		$result = $client->update($update);
	}

	// Given a case ID, return a list of hashtags
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

	public function spellCheck($client, $q)
	{
		// get a select query instance
		$query = $client->createSelect();
		$query->setRows(0);

		// add spellcheck settings
		$spellcheck = $query->getSpellcheck();
		$spellcheck->setQuery($q);
		$spellcheck->setCount(1);
		$spellcheck->setBuild(true);
		$spellcheck->setCollate(true);
		$spellcheck->setExtendedResults(true);
		$spellcheck->setCollateExtendedResults(true);

		// this executes the query and returns the result
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

	public function suggest($client, $q)
	{
		// get a suggester query instance
		$query = $client->createSuggester();
		$query->setQuery($q);
		$query->setDictionary('suggest');
		$query->setOnlyMorePopular(true);
		$query->setCount(10);
		$query->setCollate(true);

		// this executes the query and returns the result
		$resultset = $client->suggester($query);

		// display results for each suggested term
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
	
	public function addBookmark($client, $bookmark)
	{
		$result = $this->getCaseData($client, 'RAD-bookmarks');
		$currentTags = new stdClass();
		// Get the savedSearches property
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

	    $doc->setKey('id', 'RAD-bookmarks');
	    $doc->setField('savedSearches', $updatedBookmarks);
	    $doc->setFieldModifier('tag', 'set'); 

		// Add document and commit
		$update->addDocument($doc);
		$update->addCommit();

		// Runs the query and returns the result
		$result = $client->update($update);
	}
	
	// Returns all saved searches as bookmarks
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
