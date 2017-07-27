<?php
$api = app('Dingo\Api\Routing\Router');
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/




$api->version('v1', function ($api) {
    $api->post('loginUser', 'App\Http\Controllers\Api\V1\UsersController@loginUser');
});

$api->version('v1', function ($api) {
    $api->post('resetPassword', 'App\Http\Controllers\Api\V1\UsersController@resetPassword');
});

$api->version('v1', function ($api) {
    $api->post('checkUserName', 'App\Http\Controllers\Api\V1\UsersController@checkUserName');
});

$api->version('v1', function ($api) {
    $api->post('newPassword', 'App\Http\Controllers\Api\V1\UsersController@newPassword');
});

$api->version('v1', function ($api) {
    $api->post('studentRegistration', 'App\Http\Controllers\Api\V1\UsersController@studentRegistration');
});

$api->version('v1', function ($api) {
    $api->post('addGroup', 'App\Http\Controllers\Api\V1\UsersController@addGroup');
});

$api->version('v1', function ($api) {
    $api->post('addGroup', 'App\Http\Controllers\Api\V1\UsersController@addGroup');
});

$api->version('v1', function ($api) {
    $api->post('userGroup', 'App\Http\Controllers\Api\V1\UsersController@userGroup');
});

$api->version('v1', function ($api) {
    $api->post('userGroupStudent', 'App\Http\Controllers\Api\V1\UsersController@userGroupStudent');
});

Route::get('/', function () {
    return view('welcome');
});
