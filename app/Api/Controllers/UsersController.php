<?php
namespace Pulse\Api\Controllers;

use Pulse\Models\User;
use Dingo\Api\Facade\API;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends BaseController
{

    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Show User
     * @param  Request $request
     * @param  User ID  $id      ID of the user to show
     * @return Response
     */
    public function show(Request $request, $id = null)
    {
        $user = false;
        //No ID Provided
        if(is_null($id)){
            //Current User
            $user = JWTAuth::parseToken()->authenticate();
        } else{
            //Find User by ID
            $user = User::find($id);
        }

        //User not found
        if(!$user) {
            return $this->response->errorNotFound("User not found!");
        }

        //Response
        return $this->response->json(compact('user'));
    }

}