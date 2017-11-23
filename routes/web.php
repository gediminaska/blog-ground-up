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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('posts', 'PostsController');

Route::resource('categories', 'CategoriesController', ['only' => [
    'index', 'store', 'show', 'update']]);

Route::resource('tags', 'TagsController', ['only' => [
    'index', 'store', 'update']]);

Route::delete('categories', ['as' => 'categories.destroy', 'uses' => 'CategoriesController@destroy'] );

Route::post('comments', ['as'=>'comments.store', 'uses' => 'CommentsController@store']);

Route::get('blog/{slug}', ['as' => 'blog.show', 'uses' => 'BlogController@show']);

Route::get('blog', ['as' => 'blog.index', 'uses' => 'BlogController@index']);


