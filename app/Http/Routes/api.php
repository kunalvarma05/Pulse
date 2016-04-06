<?php
//API Router
$api = app('Dingo\Api\Routing\Router');

//API Version One Routes
$api->version('v1', function ($api) {

    //API Namespaced Group
    $api->group(['namespace' => "Pulse\Api\Controllers"], function ($api) {

        //API Index Page
        $api->get('/', ['as' => 'api.index', 'uses' => 'HomeController@index']);

        $api->group(['prefix' => 'user'], function ($api) {
            //Authorize
            $api->post('authorize', ['as' => 'api.user.authorize', 'uses' => 'AuthController@authorizeUser']);

            //Create
            $api->post('create', ['as' => 'api.user.create', 'uses' => 'AuthController@createUser']);
        });

        //Requires Authentication
        $api->group(['prefix' => 'user', 'middleware' => 'jwt.refresh'], function ($api) {
            //Show User
            $api->get('show/{id?}', ['as' => 'api.user.show', 'uses' => 'UsersController@show']);
        });

    });
});