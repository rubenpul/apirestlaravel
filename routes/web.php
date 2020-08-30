<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return '<h1>Hola mundo</h1>';
});


Route::post('/contact/register','ContactController@register');

Route::put('/contact/update','ContactController@update');

Route::delete('/contact/delete/{idDocument}','ContactController@delete');

Route::post('/contact/upload','ContactController@upload');

Route::get('/contact/avatar/{filename}','ContactController@getImageProfile');

Route::get('/contacts','ContactController@getAllContacts');

Route::get('/contact/detail/{id}','ContactController@getContact');

Route::get('/contact/emailphone','ContactController@getContactByEmailPhone');

Route::get('/contact/statecity','ContactController@getContactByStateCity');

Route::get('/contact/countries','ContactController@getCountries');

Route::get('/contact/states/{idCountry}','ContactController@getStates');

Route::get('/contact/cities/{idCountry}/{idState}','ContactController@getCities');

Route::delete('/contact/avatar/{filename}','ContactController@deleteImage');

Route::post('/contact/updateimage','ContactController@updateImageProfile');