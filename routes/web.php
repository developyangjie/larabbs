<?php

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

Route::get('/',"TopicsController@index");

Auth::routes();


Route::resource("users","UsersController",['only'=>['show','update','edit']]);
Route::resource('topics', 'TopicsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);

Route::resource("categories","CategoriesController",["only=>['show']"]);

Route::post("upload_image",'TopicsController@uploadImage')->name('topics.upload_image');
Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);

Route::resource('notifications',"NotificationsController",['only'=>['index']]);

Route::get("home/test","HomeController@test")->name("home.test")->middleware('auth.basic');

Route::get("home/broadcast","HomeController@broadcast")->name("home.broadcast");
Route::get("home/broadcastShow","HomeController@broadcastShow")->name("home.broadcastShow");

Route::get("search","PostController@search")->name("post.search");