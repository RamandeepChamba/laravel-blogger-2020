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
Route::resource('comments', 'CommentController');
Route::get('comments/{comment_id}/replies', 'CommentController@getReplies');
