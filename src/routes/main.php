<?php

use App\Http\Router;

Router::get('/', 'HomeController@index');
Router::post('/users/create', 'UserController@store');
Router::post('/users/login', 'UserController@login');
Router::get('/users/fetch', 'UserController@fetch');
Router::put('/users/update', 'UserController@update');
Router::delete('/users/{id}/delete', 'UserController@remove');
?>