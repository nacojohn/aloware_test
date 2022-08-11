<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentReplyController;
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

Route::get('/', function () {
    return response('hello');
});

Route::resource('posts.comments', CommentController::class);
Route::resource('posts.comments.replies', CommentReplyController::class);