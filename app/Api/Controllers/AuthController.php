<?php
namespace Pulse\Api\Controllers;

use Hash;
use Pulse\Models\User;
use Dingo\Api\Facade\API;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Pulse\Api\Requests\SignupRequest;
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

    public function signup(SignupRequest $request)
    {
        //Fetch User Data
        $userData = [
        'name' => $request->get('name'),
        'email' => $request->get('email'),
        'password' => Hash::make($request->get('password')),
        'username' => $request->get('username')
        ];

        //Create User
        $user = User::create($userData);

        //Something went wrong
        if(!$user){
            return $this->response->errorInternal("Something went wrong! Please try again!");
        }

        //Create JWT Token for the create user
        $token = JWTAuth::fromUser($user);

        //Return the Response
        return response()->json(compact('token'));
    }
}