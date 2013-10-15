<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// The main search page
Route::get('/', array('as' => 'home', 'uses' => 'SolrController@getIndex'));

// The search results page
Route::post('results', array('as' => 'results', 'uses' => 'SolrController@postResults'));

// The case information plus related cases page
Route::post('case-lookup', array('as' => 'case-results', 'uses' => 'SolrController@postCaseLookup'));