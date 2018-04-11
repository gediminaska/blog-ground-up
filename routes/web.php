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
Route::prefix('manage')->middleware('role:superadministrator|administrator|editor|author|contributor')->group(function(){
    Route::get('/', 'ManageController@index');
    Route::get('/dashboard', 'ManageController@dashboard')->name('manage.dashboard');
    Route::resource('/users', 'UserController');
    Route::resource('/permissions', 'PermissionController', ['except' => 'destroy']);
    Route::resource('/roles', 'RoleController', ['except' => 'destroy']);
    Route::resource('/posts', 'PostsController');
    Route::resource('categories', 'CategoriesController', ['only' => [
        'index', 'store', 'show', 'update']]);
    Route::delete('categories', ['as' => 'categories.destroy', 'uses' => 'CategoriesController@destroy'] );

});

Route::get('/', 'PagesController@index')->name('welcome');
Route::get('/email', 'PagesController@email')->name('email');
Route::post('/email', 'PagesController@sendEmail')->name('send.email');
Route::post('/contact', 'PagesController@sendEmail')->name('send.email');
Route::get('/contact', 'PagesController@contact')->name('contact');

Auth::routes();



Route::resource('tags', 'TagsController', ['only' => [
     'store', 'destroy']]);


Route::post('comments', ['as'=>'comments.store', 'uses' => 'CommentsController@store']);
Route::delete('comments/{id}', ['as'=>'comments.delete', 'uses' => 'CommentsController@destroy']);

Route::get('blog/{slug}', ['as' => 'blog.show', 'uses' => 'BlogController@show']);
Route::get('blog/category/{category_id}', ['as' => 'blog.category', 'uses' => 'BlogController@category']);

Route::get('blog', ['as' => 'blog.index', 'uses' => 'BlogController@index']);
Route::get('blog/filter/{filter}', ['as' => 'blog.index.filtered', 'uses' => 'BlogController@indexFiltered']);


