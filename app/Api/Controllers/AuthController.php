<?php
namespace Pulse\Api\Controllers;

use Dingo\Api\Facade\API;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends BaseController
{

    public function login(Request $request)
    {
        //Login Credentials
        $credentials = $request->only('email', 'password');

        try{
            //Try to Verify Credentials and Create a Token
            if(! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            //Something went wrong!
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        //Return the generated token
        return response()->json(compact('token'));
    }
}