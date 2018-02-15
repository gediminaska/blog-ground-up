<?php

use Illuminate\Http\Request;
use App\Http\Controllers\PostsController;

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

Route::get('posts/{post}/comments', 'CommentsController@index');
Route::get('blog/comments', 'CommentsController@latest');
Route::post('posts/{post}/comment', 'CommentsController@store');


Route::middleware('auth:api')->group(function () {
    Route::get('/posts/unique', 'PostsController@apiCheckUnique')->name('api.post');
    Route::get('/dashboard/activity/posts', 'PostsController@apiGetStats')->name('api.dashboard.posts');
    Route::get('/dashboard/activity/categories', 'PostsController@apiGetCategoryStats')->name('api.dashboard.categories');
    Route::get('/dashboard/activity/users', 'PostsController@apiGetUserStats')->name('api.dashboard.users');
    Route::get('/dashboard/activity/comments', 'PostsController@apiGetCommentStats')->name('api.dashboard.comments');
});
