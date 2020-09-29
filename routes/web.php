<?php


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->get('/branch', 'BranchController@index');
$router->get('/branch/{id}', 'BranchController@show');
$router->get('/dashboard', 'DashboardController@index');

$router->get('/partner', 'PartnerController@index');
$router->get('/partner/{id}', 'PartnerController@show');
$router->post('/partner', 'PartnerController@store');
$router->put('/partner', 'PartnerController@update');
$router->delete('/partner', 'PartnerController@destroy');

$router->post('/register', 'UserController@register');
$router->post('/login', 'UserController@login');
$router->post('/updatePassword', 'UserController@updatePassword');
