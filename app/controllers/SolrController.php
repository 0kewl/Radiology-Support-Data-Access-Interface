<?php

use Solarium\Client;

class SolrController extends BaseController {

	public function getIndex()
	{

		$keywords = SearchFieldEntity::getFields();

		$operators = array('OR', 'AND', 'NOT');

		return View::make('index', compact('keywords', 'operators'));
	}

	public function postResults()
	{
		// DO NOT MODIFY - general Solar configuration
		$config = array(
    		'endpoint' => array(
        		'localhost' => array(
            		'host' => '127.0.0.1',
            		'port' => 8983,
            		'path' => '/solr/',
       			)
    		)
		);
		// create a Solr client instance
		$client = new Client($config);

		// parse keywords and other form data
		
		$data = new SolrResultSet();
		$resultset = $data->getAllData($client);

		// render html tables
		$html = '';

		// show documents using the resultset iterator
		foreach ($resultset as $document) {
		    $html = $html . '<hr/><table>';

		    // the documents are also iterable, to get all fields
		    foreach($document AS $field => $value)
		    {
		        // this converts multivalue fields to a comma-separated string
		        if(is_array($value)) $value = implode(', ', $value);

		        $html = $html . '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
		    }
		    $html = $html . '</table>';
		}
		
		return View::make('results', compact('html'));
	}

	public function addCase() {

		$config = array(
    		'endpoint' => array(
        		'localhost' => array(
            		'host' => '127.0.0.1',
            		'port' => 8983,
            		'path' => '/solr/',
       			)
    		)
		);

		// create a client instance
		$client = new Solarium\Client($config);

		// get an update query instance
		$update = $client->createUpdate();

		// create a new document for the data
		$case = $update->createDocument();

		$case->department = "Radiology";
		$case->category = "report";
		$case->pid = "123456";
		$case->sex = "Male";
		$case->id = "999999";
		$case->did = "999999";
		$case->modality = "CT";
		$case->title = "MRI of the Head";
		$case->date = "2013-01-09T09:34:00Z";
		$case->year = "2013";
		$case->month = "01";
		$case->day = "09";
		$case->hour = "09";
		$case->history = "Subarachnoid";
		$case->site = "WRC";
		$case->physician = "112233";
		$case->body = "On the head, on the base of the neck.";
		$case->impression = "1. MRI of head. 2. On the base of the neck.";
		$case->anatomy = "skull";
		$case->side = "none";

		// add the documents and a commit command to the update query
		$update->addDocuments(array($case));
		$update->addCommit();

		// this executes the query and returns the result
		$result = $client->update($update);
	}
	
	private function docArrayToJSON($resultset)
	{
		$results = array();
		foreach($resultset as $document) {
			$item = array();
			foreach($document as $field => $value) {
				$item[$field] = $value;
			}
			$results[] = $item;
		}
		return json_encode($results);
	}
}
