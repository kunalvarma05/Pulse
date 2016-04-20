<?php
namespace Pulse\Api\Controllers;

use Auth;
use Pulse\Models\Account;
use Illuminate\Http\Request;
use Pulse\Services\Manager\ManagerFactory;
use Pulse\Bus\Commands\Manager\CopyCommand;
use Pulse\Bus\Commands\Manager\MoveCommand;
use Pulse\Api\Transformers\QuotaTransformer;
use Pulse\Bus\Commands\Manager\DeleteCommand;
use Pulse\Services\Authorization\AuthFactory;
use Pulse\Bus\Commands\Manager\GetQuotaCommand;
use Pulse\Api\Transformers\FileListTransformer;
use Pulse\Bus\Commands\Manager\ListFilesCommand;

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
        $account = $user->accounts()->findOrFail($account_id);

        //Get Quota
        $quota = dispatch(new GetQuotaCommand($account));

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
        $account = $user->accounts()->findOrFail($account_id);

        //Get Files
        $files = dispatch(new ListFilesCommand($account, $request->get('path')));

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
        $account = $user->accounts()->findOrFail($account_id);

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
        $account = $user->accounts()->findOrFail($account_id);

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

    /**
     * Delete File
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function performDelete(Request $request, $account_id)
    {
        if(!$request->has('file')) {
            return response()->json(['error' => 'no_file_specified', 'message' => "No file was specified!"], 200);
        }

        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts()->findOrFail($account_id);

        //Provider
        $provider = $account->provider;

        $file = $request->get('file');

        //Delete File
        $fileDelete = dispatch(new DeleteCommand(
            $user,
            $account,
            $provider,
            $file
            ));

        //Files not deleted
        if(!$fileDelete) {
            return response()->json(['error' => 'file_not_deleted', 'message' => "Cannot delete file!"], 200);
        }

        //Return Response
        return $this->response->array(['message' => "File Deleted!", "file" => $file]);
    }

}