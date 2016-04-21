<?php
namespace Pulse\Api\Controllers;

use Pulse\Models\User;
use Dingo\Api\Facade\API;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Pulse\Api\Transformers\UserTransformer;
use Pulse\Bus\Commands\User\DeleteUserCommand;

class UsersController extends BaseController
{

    /**
     * Initialize
     * @return Response
     */
    public function initialize()
    {

        $user = auth()->user();
        //Response
        return $this->response->item($user, new UserTransformer);
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
        if (is_null($id)) {
            //Current User
            $user = JWTAuth::parseToken()->authenticate();
        } else {
            //Find User by ID
            $user = User::find($id);
        }

        //User not found
        if (!$user) {
            return response()->json(['error' => 'user_not_found', 'message' => "User not found!"], 400);
        }

        //Response
        return $this->response->item($user, new UserTransformer);
    }

    /**
     * Delete User
     * @param  Request $request
     * @param  User ID  $id      ID of the user to delete
     * @return Response
     */
    public function delete(Request $request, $id = null)
    {
        //Find the User
        $user = User::find($id);

        //User not found
        if (!$user) {
            return response()->json(['error' => 'user_not_found', 'message' => "User not found!"], 400);
        }

        //Delete the user
        dispatch(new DeleteUserCommand($user));

        //Response
        return response()->json(['message' => "User Deleted"]);
    }
}
