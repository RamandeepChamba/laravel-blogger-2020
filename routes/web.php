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
    return view('welcome');
});

// View mail in browser
Route::get('/email', function () {
    $user = new App\User([
        'name' => 'Raman',
        'email' => 'raman@blog.com'
    ]);
    return new App\Mail\WelcomeMail($user);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/hello', 'HelloController@index');
Route::resource('blogs', 'BlogController');
Route::get('/blogs/{blog}/comments', 'BlogController@getComments');
Route::resource('comments', 'CommentController');
Route::get('/comments/{parent_id}/replies', 'CommentController@getReplies');
Route::post('/likes', 'LikeController@store');
// Route::get('/likes/{type}/{id}', 'LikeController@store');
Route::delete('/likes', 'LikeController@destroy');
