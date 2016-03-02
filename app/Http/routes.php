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
    //Entry Point
    Route::get('api/dropbox', array('uses' => "DropboxController@api"));
    Route::get('connect/dropbox', array('uses' => "DropboxController@connect"));
    Route::get('auth/callback/dropbox', array('uses' => "DropboxController@auth"));

    //Entry Point
    Route::get('api/drive', array('uses' => "DriveController@api"));
    Route::get('connect/drive', array('uses' => "DriveController@connect"));
    Route::get('auth/callback/drive', array('uses' => "DriveController@auth"));
});
