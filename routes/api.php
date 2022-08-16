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

Route::get('/books', 'BooksController@index');
Route::middleware('auth')->group(function () {
    Route::middleware('auth.admin')->group(function () {
        Route::post('/books', 'BooksController@store');
    });
    Route::post('/books/{id}/reviews', 'BooksReviewController@store')->where('id', '1|999');
    Route::delete('/books/{bookId}/reviews/{reviewId}', 'BooksReviewController@destroy')
    ->where([
        'bookId' => '1|999',
        'reviewId' => '1|999',
    ]);
});
