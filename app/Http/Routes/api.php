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
        $api->group(['prefix' => 'users'], function ($api) {
            //Authorize User
            $api->post('authorize', ['as' => 'api.users.authorize', 'uses' => 'AuthController@authorizeUser']);

            //Create User
            $api->post('create', ['as' => 'api.users.create', 'uses' => 'AuthController@createUser']);

            //Requires Authentication
            $api->group(['middleware' => ['jwt.auth']], function ($api) {
                //Show User
                $api->get('show/{id?}', ['as' => 'api.users.show', 'uses' => 'UsersController@show']);

                //Delete User
                $api->delete('delete/{id}', ['as' => 'api.users.delete', 'uses' => 'UsersController@delete']);
            });
        });

        /**
         * ********************************
         * Provider Endpoint
         * ********************************
         */
        $api->group(['prefix' => 'providers', 'middleware' => ['jwt.auth']], function ($api) {
            //Get Auth Redirect URL
            $api->get('auth-url', ['as' => 'api.providers.auth-url', 'middleware' => ['session'], 'uses' => 'ProvidersController@getAuthUrl']);
        });

        /**
         * ********************************
         * Account Endpoint
         * ********************************
         */
        $api->group(['prefix' => 'accounts', 'middleware' => ['jwt.auth']], function ($api) {
            //List Accounts
            $api->get('/', ['as' => 'api.accounts.list', 'uses' => 'AccountsController@index']);
            //Create Account
            $api->post('create', ['as' => 'api.accounts.create', 'middleware' => ['session'], 'uses' => 'AccountsController@create']);

            //Account Manager
            $api->group(['prefix' => '{account_id}/manager'], function ($api) {
                //Get Quota
                $api->get('quota', ['as' => 'api.accounts.manager.quota', 'uses' => 'ManagerController@quota']);
                //Browse Files
                $api->get('browse ', ['as' => 'api.accounts.manager.browse', 'uses' => 'ManagerController@browse']);
                //Perform Copy Action
                $api->post('copy', ['as' => 'api.accounts.manager.copy', 'uses' => 'ManagerController@performCopy']);
                //Perform Move Action
                $api->patch('move', ['as' => 'api.accounts.manager.move', 'uses' => 'ManagerController@performMove']);
                //Perform Delete Action
                $api->delete('delete', ['as' => 'api.accounts.manager.delete', 'uses' => 'ManagerController@performDelete']);
            });

        });
    });
});