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
Route::prefix('manage')->middleware('auth')->group(function(){
    Route::get('/', 'ManageController@index');
    Route::get('/dashboard', 'ManageController@dashboard')->name('manage.dashboard');
    Route::resource('/users', 'UserController');
    Route::resource('/permissions', 'PermissionController', ['except' => 'destroy']);
    Route::resource('/roles', 'RoleController', ['except' => 'destroy']);
    Route::resource('/posts', 'PostsController');
    Route::resource('/categories', 'CategoriesController');

});

Route::get('/', 'PagesController@index')->name('welcome');
Route::get('/email', 'PagesController@email')->name('email');
Route::post('/email', 'PagesController@sendEmail')->name('send.email');
Route::post('/contact', 'PagesController@sendEmail')->name('send.email');
Route::get('/contact', 'PagesController@contact')->name('contact');
Route::get('/errors', 'PagesController@errors')->name('errors');
Route::get('/my-account', 'Auth\MyAccountController@show')->name('my.account');
Route::post('/my-account', 'Auth\MyAccountController@update')->name('my.account.update');
Route::delete('/my-account', 'Auth\MyAccountController@delete')->name('delete.link');

Auth::routes();
Route::get('auth/facebook', 'Auth\LoginController@redirectToFacebook')->name('login.facebook');
Route::get('auth/facebook/callback', 'Auth\LoginController@handleFacebookCallback');
Route::get('auth/github', 'Auth\LoginController@redirectToGithub')->name('login.github');
Route::get('auth/github/callback', 'Auth\LoginController@handleGithubCallback');

Route::resource('tags', 'TagsController', ['only' => ['store', 'destroy']]);

Route::post('comments', ['as'=>'comments.store', 'uses' => 'CommentsController@store']);
Route::delete('comments', ['as'=>'comments.delete', 'uses' => 'CommentsController@destroy']);

Route::get('blog/search', ['as' => 'blog.search', 'uses' => 'BlogController@search']);
Route::get('blog/{slug}', ['as' => 'blog.show', 'uses' => 'BlogController@show']);
Route::get('blog/category/{category_id}', ['as' => 'blog.category', 'uses' => 'BlogController@category']);
Route::get('blog/category/{category_id}/filter/{filter}', ['as' => 'blog.category.filtered', 'uses' => 'BlogController@categoryFiltered']);

Route::get('blog', ['as' => 'blog.index', 'uses' => 'BlogController@index']);
Route::get('blog/filtered/{filter}', ['as' => 'blog.index.filtered', 'uses' => 'BlogController@indexFiltered']);
