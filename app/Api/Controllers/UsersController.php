<?php
namespace Pulse\Api\Controllers;

use Pulse\Models\User;
use Dingo\Api\Facade\API;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Pulse\Api\Transformers\UserTransformer;
use Pulse\Api\Transformers\AccountTransformer;
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

    public function listUsers()
    {
        $users = User::where('is_admin', false)->orderBy('id', 'DESC')->get();

        return $this->response->collection($users, new UserTransformer);
    }

    public function userStats()
    {
        //Carbon
        $carbon = new \Carbon\Carbon;

        //Including today, and the past 6 days
        $range = $carbon->now()->subDays(6);

        $dates = array();

        for ($i=6;$i>=0;$i--) {
            $day = $carbon->today()->subDays($i)->format("D");
            $dates[$day] = 0;
        }

        $stats = \DB::table('users')
        ->where('created_at', '>=', $range)
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->limit(7)
        ->get(array(
            \DB::raw('Date(created_at) as date'),
            \DB::raw('COUNT(*) as value')
            ));

        $return = array();
        $labels = array();
        $counts = array();

        foreach ($stats as $value) {
            $day = $carbon->createFromFormat("Y-m-d", $value->date)->format('D');

            $dates[$day] = $value->value;
        }

        foreach ($dates as $key => $value) {
            $labels[] = $key;
            $counts[] = $value;
        }

        $return['labels'] = $labels;
        $return['counts'] = $counts;

        return response()->json(['data' => $return]);
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
        return $this->response->item($user, new UserTransformer);
    }
}
