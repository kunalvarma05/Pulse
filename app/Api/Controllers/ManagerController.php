<?php
namespace Pulse\Api\Controllers;

use Auth;
use Pulse\Models\Account;
use Illuminate\Http\Request;
use Pulse\Services\Manager\ManagerFactory;
use Pulse\Api\Transformers\QuotaTransformer;
use Pulse\Services\Authorization\AuthFactory;
use Pulse\Api\Transformers\FileListTransformer;

class ManagerController extends BaseController
{

    protected $availableActions = ['copy', 'move', 'rename', 'delete'];

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

        //Path
        $path = $request->has('path') ? $request->get('path') : null;

        //Fetch the Quota
        $files = $manager->listChildren($path);

        //Files not found
        if(!$files) {
            return response()->json(['error' => 'no_files_found', 'message' => "No files found!"], 200);
        }

        //Array to Collection
        $fileCollection = collect($files);

        return $this->response->collection($fileCollection, new FileListTransformer);
    }

    public function performAction(Request $request, $account_id, $action)
    {
        if(!$request->has('file')) {
            return response()->json(['error' => 'no_file_specified', 'message' => "No file was specified!"], 200);
        }

        if(!in_array($action, $this->availableActions)) {
            return response()->json(['error' => 'invalid_action', 'message' => "Invalid action specified!"], 200);
        }

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

        //File
        $file = $request->get('file');
        //Location
        $location = $request->has('location') ? $request->get('location') : null;

        //Additional Data
        $data = [];

        //Title
        if($request->has('title'))
            $data['title'] = $request->get('title');

        //Copy File
        $fileCopy = $manager->copy($file, $location, $data);

        //Files not copied
        if(!$fileCopy) {
            return response()->json(['error' => 'file_not_copied', 'message' => "Cannot copy file!"], 200);
        }

        return $this->response->item($fileCopy, new FileListTransformer);


    }

}