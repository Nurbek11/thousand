<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/authors', 'Api\AuthController@authors');
Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
Route::get('/posts', 'Api\PostController@index')->middleware('auth:api');
Route::post('/posts/inRubric', 'Api\PostController@searchInRubric')->middleware('auth:api');
Route::post('/posts/byRubric', 'Api\PostController@searchByRubric')->middleware('auth:api');
Route::post('/posts/byId', 'Api\PostController@showById')->middleware('auth:api');
Route::post('/posts/byTitle', 'Api\PostController@showByTitle')->middleware('auth:api');
Route::post('/createPost', 'Api\PostController@create')->middleware('auth:api');
Route::post('/createRubric', 'Api\RubricController@create')->middleware('auth:api');


