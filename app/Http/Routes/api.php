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

            //Forgot Password
            $api->post('forgot-password', ['as' => 'api.users.forgot-password', 'uses' => 'AuthController@forgotPassword']);

            //Reset Password
            $api->post('reset-password', ['as' => 'api.users.reset-password', 'uses' => 'AuthController@resetPassword']);

            //Requires Authentication
            $api->group(['middleware' => ['jwt.auth']], function ($api) {
                //Initialize
                $api->get('initialize', ['as' => 'api.users.initialize', 'middleware' => ['jwt.auth'], 'uses' => 'UsersController@initialize']);
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
            //List Providers
            $api->get('/', ['as' => 'api.providers.list', 'uses' => 'ProvidersController@index']);
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
                //Get Account Info
                $api->get('info', ['as' => 'api.accounts.manager.info', 'uses' => 'ManagerController@getAccountInfo']);
                //Get File Info
                $api->get('file-info ', ['as' => 'api.accounts.manager.file-info', 'uses' => 'ManagerController@getFileInfo']);
                //Browse Files
                $api->get('browse ', ['as' => 'api.accounts.manager.browse', 'uses' => 'ManagerController@browse']);
                //Perform Copy Action
                $api->post('copy', ['as' => 'api.accounts.manager.copy', 'uses' => 'ManagerController@performCopy']);
                //Perform Move Action
                $api->patch('move', ['as' => 'api.accounts.manager.move', 'uses' => 'ManagerController@performMove']);
                //Perform Rename Action
                $api->patch('rename', ['as' => 'api.accounts.manager.rename', 'uses' => 'ManagerController@performRename']);
                //Perform Delete Action
                $api->delete('delete', ['as' => 'api.accounts.manager.delete', 'uses' => 'ManagerController@performDelete']);
                //Perform Create Folder Action
                $api->post('create-folder', ['as' => 'api.accounts.manager.create-folder', 'uses' => 'ManagerController@createFolder']);
                //Get Download Link
                $api->get('download ', ['as' => 'api.accounts.manager.download', 'uses' => 'ManagerController@getDownloadLink']);
                //Get Share Link
                $api->get('share-link ', ['as' => 'api.accounts.manager.share-link', 'uses' => 'ManagerController@getShareLink']);
                //Upload File
                $api->post('upload ', ['as' => 'api.accounts.manager.upload', 'uses' => 'ManagerController@uploadFile']);
                //Transfer File
                $api->post('transfer ', ['as' => 'api.accounts.manager.transfer', 'uses' => 'ManagerController@transferFile']);
            });
        });
    });
});
