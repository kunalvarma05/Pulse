<?php
//API Router
$api = app('Dingo\Api\Routing\Router');

//API Version One Routes
$api->version('v1', function ($api) {

    //API Namespaced Group
    $api->group(['namespace' => "Pulse\Api\Controllers"], function ($api) {

        //API Index Page
        $api->get('/', ['as' => 'api.index', 'uses' => 'HomeController@index']);

        /**
         * ********************************
         * User Endpoint
         * ********************************
         */
        $api->group(['prefix' => 'user'], function ($api) {
            //Authorize User
            $api->post('authorize', ['as' => 'api.user.authorize', 'uses' => 'AuthController@authorizeUser']);

            //Create User
            $api->post('create', ['as' => 'api.user.create', 'uses' => 'AuthController@createUser']);

            //Requires Authentication
            $api->group(['middleware' => ['jwt.auth']], function ($api) {
                //Show User
                $api->get('show/{id?}', ['as' => 'api.user.show', 'uses' => 'UsersController@show']);

                //Delete User
                $api->delete('delete/{id}', ['as' => 'api.user.delete', 'uses' => 'UsersController@delete']);
            });
        });

        /**
         * ********************************
         * Account Endpoint
         * ********************************
         */
        $api->group(['prefix' => 'account', 'middleware' => ['jwt.auth']], function ($api) {
            //Create Account
            $api->post('create', ['as' => 'api.account.create', 'uses' => 'AccountsController@create']);
        });
    });
});