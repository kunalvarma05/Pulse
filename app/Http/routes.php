<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});

//Serve the Application
Route::get('/', array('uses' => "HomeController@index"));

//Handle OAuth Callbacks
Route::get('auth/callback/{provider}', array('uses' => "HomeController@authCallback"));

//Password Reset
Route::get('password/reset/{token}', array('uses' => "HomeController@passwordReset", 'as' => 'password-reset'));

/**
 * *****************************************************
 * Include all the Routes
 * *****************************************************
 * Includes all the routes from the app/routes directory
 */
foreach (\File::allFiles(app_path('Http/Routes')) as $partial) {
    require_once $partial->getPathname();
}
