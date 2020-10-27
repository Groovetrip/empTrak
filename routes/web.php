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

Auth::routes(['register' => false]);

Route::middleware('auth')->group(function() {

    // Dashboard
    Route::get('/', 'HomeController@index')->name('home');

    // Employees
    Route::resource('employees', 'EmployeeController')->only(['index', 'show']);

});
