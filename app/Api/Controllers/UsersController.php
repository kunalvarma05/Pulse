<?php
namespace Pulse\Api\Controllers;

use Dingo\Api\Facade\API;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends BaseController
{

    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function profile(Request $request)
    {
        return JWTAuth::parseToken()->authenticate();
    }

}