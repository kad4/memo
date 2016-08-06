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

Route::get('/',function(){
	return View::make('index');
});

Route::group(array('before' => 'auth'), function()
{
	Route::get('home','HomeController@showhome');
	Route::get('group','HomeController@loadgroup');
	Route::get('addgroup','HomeController@addgroup');
	Route::get('deletegroup','HomeController@deletegroup');

	Route::post('addgroup','GroupController@addgroup');
	Route::post('deletegroup','GroupController@deletegroup');

	Route::post('getpost','PostController@getpost');

	Route::post('addpost','PostController@addpost');
	Route::post('updatepost','PostController@updatepost');

	Route::post('usernotified','UserController@usernotified');
	Route::post('processinvite','UserController@processinvite');

});


Route::group(array('before' => 'auth|isadmin'), function()
{
	Route::get('editgroup','HomeController@editgroup');

	Route::post('sendinvite','UserController@sendinvite');
	Route::post('changerole','UserController@changerole');
	Route::post('deleteuser','UserController@deleteuser');

	Route::post('editgroup','GroupController@editgroup');

	Route::post('loadposts','PostController@loadposts');
});

Route::post('login','UserController@login');
Route::post('logout','UserController@logout');

Route::get('signup',function(){
	return View::make('signup');
});

Route::post('signup','UserController@signup');


//Routes to interact with desktop client
Route::any('newnotes','ClientController@newnotes');
Route::any('allnotes','ClientController@allnotes');
Route::any('deleted','ClientController@deletednotes');
Route::any('checkuser','ClientController@checkuser');
Route::any('checknotes','ClientController@checknotes');
Route::any('checkgroups','ClientController@checkgroups');
Route::any('notifications','ClientController@notifications');
Route::any('clearnotifications','ClientController@clearnotifications');

