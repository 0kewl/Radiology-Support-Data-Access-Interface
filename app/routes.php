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
Route::get('results', array('as' => 'results', 'uses' => 'SolrController@getResults'));

// The case information and related cases page
Route::get('case', array('as' => 'case-results', 'uses' => 'SolrController@getCase'));

// Get cases by hashtag
Route::get('hashtag-cases', array('as' => 'hashtag-results', 'uses' => 'SolrController@getCasesByHashtag'));

// Get hashtags
Route::get('get-hashtags', array('as' => 'get-hashtags', 'uses' => 'SolrController@getHashtags'));

// Get spell check
Route::get('spellcheck', array('as' => 'spellcheck', 'uses' => 'SolrController@getSpellCheck'));

// Add hashtags
Route::post('add-hashtags', array('as' => 'add-hashtags', 'uses' => 'SolrController@postAddHashtags'));

// The saved searches page
Route::get('saved', array('as' => 'saved-search', 'uses' => 'SolrController@getSavedSearches'));
