<?php
namespace Pulse\Api\Controllers;

use Auth;
use Pulse\Models\Account;
use Illuminate\Http\Request;
use Pulse\Services\Manager\ManagerFactory;
use Pulse\Api\Transformers\QuotaTransformer;
use Pulse\Services\Authorization\AuthFactory;

class ManagerController extends BaseController
{

    /**
     * Fetch Account Quota
     * @return Response
     */
    public function quota(Request $request, $account_id)
    {
        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts->find($account_id);

        //Account not found
        if(!$account)
        {
            return response()->json(['error' => 'account_not_found', 'message' => "Account not found!"], 404);
        }

        //Provider
        $provider = $account->provider;

        //Authorization
        $authFactory = AuthFactory::create($provider->alias);
        $access_token = $authFactory->refreshAccessToken($account->access_token);

        //Manager
        $manager = ManagerFactory::create($provider->alias, $access_token);

        //Fetch the Quota
        $quota = $manager->getQuota();

        return $this->response->item($quota, new QuotaTransformer);
    }

    /**
     * Browse Account Files
     * @return Response
     */
    public function browse(Request $request, $account_id)
    {
        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts->find($account_id);

        //Account not found
        if(!$account)
        {
            return response()->json(['error' => 'account_not_found', 'message' => "Account not found!"], 404);
        }

        //Provider
        $provider = $account->provider;

        //Authorization
        $authFactory = AuthFactory::create($provider->alias);
        $access_token = $authFactory->refreshAccessToken($account->access_token);

        //Manager
        $manager = ManagerFactory::create($provider->alias, $access_token);

        //Fetch the Quota
        $files = $manager->listChildren();

        return $files;

        return $this->response->item($files, new QuotaTransformer);
    }

}