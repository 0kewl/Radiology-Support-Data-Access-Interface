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

// Get autocomplete
Route::get('autocomplete', array('as' => 'autocomplete', 'uses' => 'SolrController@getAutocomplete'));

// Get spell check
Route::get('spellcheck', array('as' => 'spellcheck', 'uses' => 'SolrController@getSpellCheck'));

// Add hashtags
Route::post('add-hashtags', array('as' => 'add-hashtags', 'uses' => 'SolrController@postAddHashtags'));

// Get all bookmarks and render the saved searches page
Route::get('get-bookmarks', array('as' => 'get-bookmarks', 'uses' => 'SolrController@getSavedSearches'));

// Add bookmarks
Route::post('add-bookmark', array('as' => 'add-bookmark', 'uses' => 'SolrController@postAddBookmark'));

// Delete bookmark
Route::post('delete-bookmark', array('as' => 'delete-bookmark', 'uses' => 'SolrController@postDeleteBookmark'));

// Get saved hashtags page
Route::get('saved-hashtags', array('as' => 'get-saved-hashtags', 'uses' => 'SolrController@getSavedHashtags'));
