<?php
namespace Pulse\Api\Controllers;

use Auth;
use Pulse\Models\Account;
use Illuminate\Http\Request;
use Pulse\Services\Manager\ManagerFactory;
use Pulse\Bus\Commands\Manager\CopyCommand;
use Pulse\Bus\Commands\Manager\MoveCommand;
use Pulse\Api\Transformers\QuotaTransformer;
use Pulse\Services\Authorization\AuthFactory;
use Pulse\Api\Transformers\FileListTransformer;

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

    /**
     * Perform Copy Action
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function performCopy(Request $request, $account_id)
    {
        if(!$request->has('file')) {
            return response()->json(['error' => 'no_file_specified', 'message' => "No file was specified!"], 200);
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

        //Copy File
        $fileCopy = dispatch(new CopyCommand(
            $user,
            $account,
            $provider,
            $request->get('file'),
            $request->get('location'),
            $request->get('title')
            ));

        //Files not copied
        if(!$fileCopy) {
            return response()->json(['error' => 'file_not_copied', 'message' => "Cannot copy file!"], 200);
        }

        //Return Response
        return $this->response->item($fileCopy, new FileListTransformer);

    }

    /**
     * Perform Move Action
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function performMove(Request $request, $account_id)
    {
        if(!$request->has('file') || !$request->has('location')) {
            return response()->json(['error' => 'no_file_or_location_specified', 'message' => "File or location was not specified!"], 200);
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

        //Move File
        $fileMove = dispatch(new MoveCommand(
            $user,
            $account,
            $provider,
            $request->get('file'),
            $request->get('location')
            ));

        //Files not moved
        if(!$fileMove) {
            return response()->json(['error' => 'file_not_moved', 'message' => "Cannot move file!"], 200);
        }

        //Return Response
        return $this->response->item($fileMove, new FileListTransformer);

    }

}