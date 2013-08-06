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

Route::get('/', 'HomeController@showWelcome');
Route::get('/contact', 'ContactController@showContact');

//Admin authentication
Route::get('/login', array('as' => 'login', 'uses' => 'AuthController@showLogin'));
Route::post('/login', 'AuthController@postLogin');
Route::get('/logout', 'AuthController@getLogout');

Route::group(array('before' => 'auth'), function()
{
    Route::get('/create', 'PostsController@create');
});