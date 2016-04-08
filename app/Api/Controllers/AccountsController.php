<?php
namespace Pulse\Api\Controllers;

use Auth;
use Pulse\Models\Account;
use Pulse\Models\Provider;
use Tymon\JWTAuth\Facades\JWTAuth;
use Pulse\Api\Requests\Account\CreateAccountRequest;
use Pulse\Bus\Commands\Account\CreateAccountCommand;

class AccountsController extends BaseController
{
    public function create(CreateAccountRequest $request)
    {
        //Current User
        $user = Auth::user();
        //Provider
        $provider = Provider::find($request->get('provider'));

        //Dispatch CreateAccessTokenCommand
        //@todo Generate Access Token from auth code
        $access_token = $request->get('code');
        //@todo
        $uid = "xyz1234";

        //Dispatch CreateAccountCommand
        $account = dispatch(new CreateAccountCommand(
            $user,
            $provider,
            $request->get('name'),
            $uid
            $access_token
            ));

        //Something went wrong
        if(!$account) {
            return response()->json(['error' => 'could_not_create_account', 'message' => "Something went wrong!"], 500);
        }

        //Return the Response
        return response()->json(compact('account'));
    }
}