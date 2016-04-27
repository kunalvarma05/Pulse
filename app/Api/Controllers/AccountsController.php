<?php
namespace Pulse\Api\Controllers;

use Auth;
use Pulse\Models\Account;
use Pulse\Models\Provider;
use Tymon\JWTAuth\Facades\JWTAuth;
use Pulse\Api\Transformers\AccountTransformer;
use Pulse\Api\Requests\Account\CreateAccountRequest;
use Pulse\Bus\Commands\Account\ConnectAccountCommand;

class AccountsController extends BaseController
{

    /**
     * List Accounts
     * @return Response
     */
    public function index()
    {
        $accounts = Auth::user()->accounts;

        return $this->response->collection($accounts, new AccountTransformer);
    }

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
        $provider = Provider::where('alias', $request->get('provider'))->firstOrFail();

        //Dispatch ConnectAccountCommand
        $account = dispatch(new ConnectAccountCommand(
            $request->get('code'),
            $request->get('state'),
            $request->get('name'),
            $user,
            $provider
            ));

        //Something went wrong
        if ($account['error']) {
            $message = $account['message'] ? $account['message'] : "Something went wrong!";
            return response()->json(['error' => 'could_not_create_account', 'message' => $message], 500);
        }

        //Return the Response
        return $this->response->item($account['data'], new AccountTransformer);
    }
}
