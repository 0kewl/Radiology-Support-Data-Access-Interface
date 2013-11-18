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

		// Get the dismax component
		// From Solr Documentation: "Disjunction refers to the fact that your search is executed
		// across multiple fields, e.g. title, body and keywords, with different relevance weights."
		// Read more at http://wiki.apache.org/solr/DisMax
		$dismax = $query->getDisMax();
		$dismax->setQueryFields(array_keys(SearchFieldEntity::getFields()));

		$query->setRows(10);

		// Set a boost query
		// We might enable this feature in the future...disabled for now
		// $dismax->setBoostQuery('');

		// Override the default setting of 'dismax' to enable 'edismax'
		// From Solr Documentation: "Edismax searches for the query words across multiple fields with
		// different boosts, based on the significance of each field. Additional options let you influence
		// the score based on rules specific to each use case (independent of user input)."
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
		$spellcheck->setCount(10);
		$spellcheck->setBuild(true);
		$spellcheck->setCollate(true);
		$spellcheck->setExtendedResults(true);
		$spellcheck->setCollateExtendedResults(true);

		// this executes the query and returns the result
		$resultset = $client->select($query);
		$spellcheckResult = $resultset->getSpellcheck();

		if ($spellcheckResult->getCorrectlySpelled()) {
		    //echo 'yes';
		}
		else {
		    //echo 'no';
		}

		foreach($spellcheckResult as $suggestion) {
		    foreach ($suggestion->getWords() as $word) {
		    	// Could use this later on
		    }
		}

		$collations = $spellcheckResult->getCollations();

		foreach($collations as $collation) {
		  // Could use this later on
		}
		$corrections = array();

		if (!empty($collations)) {
			foreach($collation->getCorrections() as $input => $correction) {
				$element = array(
					"key" => $correction,
					"value" => $correction,
				);
				array_push($corrections, $element);
			}
		}
		return $corrections;
	}
	
	public function addBookmark($url, $newBookmark)
	{
		// We need to get the current case's tags
		$result = $this->getUrl($url);
		$currentBookmark = new stdClass();

		// The list of all hashtags, both new and existing
		$updatedHashtags = array();

		// Tags already existing in Solr
		if (!empty($currentBookmark)) {
			foreach ($currentBookmark as $b) {
				array_push($updatedBookmark, $b);
			}
		}
		// Turn the list of new hashtags into an array
		//$updatedHashtags = array_merge(explode(',', $newHashtags), $updatedHashtags);

		$update = $client->createUpdate();
		$doc= $update->createDocument();

		$doc->setKey('URL', $url);              

	    $doc->setField('savedSearches', $updatedBookmark);
	    $doc->setFieldModifier('savedSearches', 'set');     

		// Add document and commit
		$update->addDocument($doc);
		$update->addCommit();

		// Runs the query and returns the result
		$result = $client->update($update);
	}
}
