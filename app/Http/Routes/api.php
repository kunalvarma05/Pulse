<?php
//API Router
$api = app('Dingo\Api\Routing\Router');

//API Version One Routes
$api->version('v1', function ($api) {

    //API Namespaced Group
    $api->group(['namespace' => "Pulse\Api\Controllers"], function ($api) {

        //API Index Page
        $api->get('/', ['as' => 'api.index', 'uses' => 'HomeController@index']);

        $api->group(['prefix' => 'auth'], function ($api) {
            //Login
            $api->post('login', ['as' => 'api.login', 'uses' => 'AuthController@login']);

            //Signup
            $api->post('signup', ['as' => 'api.signup', 'uses' => 'AuthController@signup']);
        });

        //Require Authentication
        $api->group(['prefix' => 'user', 'middleware' => 'jwt.refresh'], function ($api) {
            $api->get('/profile', ['as' => 'api.user.profile', 'uses' => 'UsersController@profile']);
        });

    });
});