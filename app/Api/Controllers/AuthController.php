<?php
namespace Pulse\Api\Controllers;

use Hash;
use Pulse\Models\User;
use Dingo\Api\Facade\API;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Pulse\Api\Requests\SignupRequest;
use Pulse\Events\User\UserWasCreatedEvent;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends BaseController
{

    /**
     * Authorize User
     * @param  Request $request
     *
     * @return Response
     */
    public function authorizeUser(Request $request)
    {
        //Login Credentials
        $credentials = $request->only('email', 'password');

        try{
            //Try to Verify Credentials and Create a Token
            if(! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials', 'message' => "Invalid Credentials!"], 401);
            }
        } catch (JWTException $e) {
            //Something went wrong!
            return response()->json(['error' => 'could_not_create_token', 'message' => "Something went wrong!"], 500);
        }

        //Return the generated token
        return response()->json(compact('token'));
    }

    /**
     * Create User
     * @param  SignupRequest $request
     * @return Response
     */
    public function createUser(SignupRequest $request)
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
            return response()->json(['error' => 'could_not_create_user', 'message' => "Something went wrong!"], 500);
        }

        //Fire the UserWasCreatedEvent
        event(new UserWasCreatedEvent($user));

        //Create JWT Token for the create user
        $token = JWTAuth::fromUser($user);

        //Return the Response
        return response()->json(compact('token'));
    }
}