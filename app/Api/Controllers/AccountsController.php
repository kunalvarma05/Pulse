<?php
namespace Pulse\Api\Controllers;

use Auth;
use Pulse\Models\Account;
use Pulse\Models\Provider;
use Tymon\JWTAuth\Facades\JWTAuth;
use Pulse\Services\Authorization\ResolverInterface;
use Pulse\Api\Requests\Account\CreateAccountRequest;
use Pulse\Bus\Commands\Account\CreateAccountCommand;

class AccountsController extends BaseController
{

    /**
     * Create new Account
     * @param  CreateAccountRequest $request
     * @param  ResolverInterface    $resolver
     * @return Response
     */
    public function create(CreateAccountRequest $request, ResolverInterface $resolver)
    {
        //Current User
        $user = Auth::user();
        //Provider
        $provider = Provider::find($request->get('provider'));

        //Resolve Auth Provider
        $authProvider = $resolver->resolve(strtolower($provider->alias));

        //@todo Dispatch CreateAccessTokenCommand
        $access_token = $authProvider->getAccessToken($request->get('code'), $request->get('state'));

        //@todo
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