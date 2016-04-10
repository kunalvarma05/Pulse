<?php
namespace Pulse\Api\Controllers;

use Auth;
use Pulse\Models\Account;
use Pulse\Models\Provider;
use Tymon\JWTAuth\Facades\JWTAuth;
use Pulse\Services\Authorization\ResolverInterface;
use Pulse\Api\Requests\Account\CreateAccountRequest;
use Pulse\Bus\Commands\Account\CreateAccountCommand;
use Pulse\Bus\Commands\Provider\GenerateAccessTokenCommand;

class AccountsController extends BaseController
{

    /**
     * Create new Account
     * @param  CreateAccountRequest $request
     * @return Response
     */
    public function create(CreateAccountRequest $request)
    {
        //Current User
        $user = Auth::user();
        //Provider
        $provider = Provider::find($request->get('provider'));

        //Dispatch GenerateAccessTokenCommand
        $access_token = $account = dispatch(new GenerateAccessTokenCommand(
            $request->get('code'),
            $request->get('state'),
            $provider
            ));

        //@todo Cloud Account User ID
        $uid = mt_rand();

        //Dispatch CreateAccountCommand
        $account = dispatch(new CreateAccountCommand(
            $user,
            $provider,
            $request->get('name'),
            $uid,
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