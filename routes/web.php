<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', 'RegisterController@showLoginForm');
Route::post('/login', 'RegisterController@login');
Route::get('/register', 'RegisterController@showRegistrationForm');
Route::post('/register', 'RegisterController@register');
