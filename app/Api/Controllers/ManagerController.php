<?php
namespace Pulse\Api\Controllers;

use Auth;
use Pulse\Models\Account;
use Pulse\Models\Transfer;
use Illuminate\Http\Request;
use Pulse\Services\Manager\ManagerFactory;
use Pulse\Bus\Commands\Manager\CopyCommand;
use Pulse\Bus\Commands\Manager\MoveCommand;
use Pulse\Bus\Commands\Manager\RenameCommand;
use Pulse\Api\Transformers\QuotaTransformer;
use Pulse\Api\Transformers\AccountTransformer;
use Pulse\Bus\Commands\Manager\DeleteCommand;
use Pulse\Services\Authorization\AuthFactory;
use Pulse\Bus\Commands\Manager\GetQuotaCommand;
use Pulse\Api\Transformers\FileListTransformer;
use Pulse\Bus\Commands\Manager\ListFilesCommand;
use Pulse\Bus\Commands\Manager\UploadFileCommand;
use Pulse\Bus\Commands\Manager\GetFileInfoCommand;
use Pulse\Bus\Commands\Manager\CreateFolderCommand;
use Pulse\Bus\Commands\Manager\TransferFileCommand;
use Pulse\Bus\Commands\Manager\GetShareLinkCommand;
use Pulse\Bus\Commands\Manager\GetDownloadLinkCommand;
use Pulse\Bus\Commands\Manager\ScheduleTransferCommand;

class ManagerController extends BaseController
{
    /**
     * Fetch Account Details
     * @return Response
     */
    public function getAccountInfo(Request $request, $account_id)
    {
        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts()->findOrFail($account_id);

        //Transform Account for Response
        $accountTransformer = new AccountTransformer();
        $accountResponse = $accountTransformer->transform($account);

        //If account quota is required
        if($request->has('quota')) {
            //Get Quota
            $quota = dispatch(new GetQuotaCommand($account));

            //Transform Quota for Response
            $quotaTransformer = new QuotaTransformer();
            $quotaResponse = $quotaTransformer->transform($quota);

            //Set the Account Quota
            $accountResponse['quota'] = $quotaResponse;
        }

        return $this->response->array(['data' => $accountResponse]);
    }

    /**
     * Get File Info
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function getFileInfo(Request $request, $account_id)
    {
        if (!$request->has('file')) {
            return response()->json(['message' => "No file was specified!"], 400);
        }

        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts()->findOrFail($account_id);

        //Get File Info
        $fileInfo = dispatch(new GetFileInfoCommand(
            $user,
            $account,
            $request->get('file')
            ));

        //File not found
        if (!$fileInfo) {
            return response()->json(['message' => "File not found!"], 400);
        }

        //Return Response
        return $this->response->item($fileInfo, new FileListTransformer);
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
        if (!$files) {
            return response()->json(['data' => [], 'message' => "No files found!"], 200);
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
        if (!$request->has('file')) {
            return response()->json(['message' => "No file was specified!"], 400);
        }

        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts()->findOrFail($account_id);

        //Copy File
        $fileCopy = dispatch(new CopyCommand(
            $user,
            $account,
            $request->get('file'),
            $request->get('location'),
            $request->get('title')
            ));

        //Files not copied
        if (!$fileCopy) {
            return response()->json(['message' => "Cannot copy file!"], 400);
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
        if (!$request->has('file') || !$request->has('location')) {
            return response()->json(['message' => "File or location was not specified!"], 400);
        }

        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts()->findOrFail($account_id);

        //Move File
        $fileMove = dispatch(new MoveCommand(
            $user,
            $account,
            $request->get('file'),
            $request->get('location')
            ));

        //Files not moved
        if (!$fileMove) {
            return response()->json(['message' => "Cannot move file!"], 400);
        }

        //Return Response
        return $this->response->item($fileMove, new FileListTransformer);
    }

    /**
     * Perform Rename Action
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function performRename(Request $request, $account_id)
    {
        if (!$request->has('file') || !$request->has('title')) {
            return response()->json(['message' => "File or title was not specified!"], 400);
        }

        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts()->findOrFail($account_id);

        //Rename File
        $fileRename = dispatch(new RenameCommand(
            $user,
            $account,
            $request->get('file'),
            $request->get('title')
            ));

        //Files not renamed
        if (!$fileRename) {
            return response()->json(['message' => "Cannot rename file!"], 400);
        }

        //Return Response
        return $this->response->item($fileRename, new FileListTransformer);
    }

    /**
     * Delete File
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function performDelete(Request $request, $account_id)
    {
        if (!$request->has('file')) {
            return response()->json(['message' => "No file was specified!"], 400);
        }

        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts()->findOrFail($account_id);

        $file = $request->get('file');

        //Delete File
        $fileDelete = dispatch(new DeleteCommand(
            $user,
            $account,
            $file
            ));

        //Files not deleted
        if (!$fileDelete) {
            return response()->json(['message' => "Cannot delete file!"], 400);
        }

        //Return Response
        return $this->response->array(['message' => "File Deleted!", "file" => $file]);
    }

    /**
     * Create Folder
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function createFolder(Request $request, $account_id)
    {
        if (!$request->has('title')) {
            return response()->json(['message' => "No title was specified!"], 400);
        }

        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts()->findOrFail($account_id);

        //Copy File
        $folder = dispatch(new CreateFolderCommand(
            $user,
            $account,
            $request->get('title'),
            $request->get('location')
            ));

        //Folder not created
        if (!$folder) {
            return response()->json(['message' => "Cannot create folder!"], 400);
        }

        //Return Response
        return $this->response->item($folder, new FileListTransformer);
    }

    /**
     * Get Download Link
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function getDownloadLink(Request $request, $account_id)
    {
        if (!$request->has('file')) {
            return response()->json(['message' => "No file was specified!"], 400);
        }

        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts()->findOrFail($account_id);

        //Get Download Link
        $downloadLink = dispatch(new GetDownloadLinkCommand(
            $account,
            $request->get('file')
            ));

        //Download link unavailable
        if (!$downloadLink) {
            return response()->json(['message' => "Download link unavailable!"], 400);
        }

        //Return Response
        return $this->response->array(['link' => $downloadLink]);
    }

    /**
     * Get Share Link
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function getShareLink(Request $request, $account_id)
    {
        if (!$request->has('file')) {
            return response()->json(['message' => "No file was specified!"], 400);
        }

        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts()->findOrFail($account_id);

        //Get Share Link
        $shareLink = dispatch(new GetShareLinkCommand(
            $account,
            $request->get('file')
            ));

        //Share link unavailable
        if (!$shareLink) {
            return response()->json(['message' => "Sharing link unavailable!"], 400);
        }

        //Return Response
        return $this->response->array(['link' => $shareLink]);
    }

    /**
     * Upload File
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function uploadFile(Request $request, $account_id)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['message' => "No file was specified!"], 400);
        }

        //File
        $file = $request->file('file');

        //Invalid File
        if (!$file->isValid()) {
            return response()->json(['message' => "Please upload a valid file!"], 400);
        }

        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts()->findOrFail($account_id);

        //Additional Data
        $data = [
        'fileSize' => $file->getSize(),
        'mimeType' => $file->getMimeType(),
        'fileExtension' => $file->guessExtension()
        ];

        //Title
        $title = $request->has('title') ? $request->get('title') : $file->getClientOriginalName();

        //Upload File
        $uploadedFile = dispatch(new UploadFileCommand(
            $user,
            $account,
            $file->path(),
            $request->get('location'),
            $title,
            $data
            ));

        //Files not uploaded
        if (!$uploadedFile) {
            return response()->json(['message' => "File not uploaded!"], 400);
        }

        //Return Response
        return $this->response->item($uploadedFile, new FileListTransformer);
    }

    /**
     * Transfer File across Providers
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function transferFile(Request $request, $account_id)
    {
        if (!$request->has('file') || !$request->has('account')) {
            return response()->json(['message' => "File or account was not specified!"], 400);
        }

        //File
        $file = $request->get('file');
        //New Account ID
        $newAccountID = $request->get('account');

        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts()->findOrFail($account_id);
        //New Account
        $newAccount = $user->accounts()->findOrFail($newAccountID);

        //Transfer File
        $transferedFile = dispatch(new TransferFileCommand(
            $user,
            $account,
            $newAccount,
            $file,
            $request->get('location'),
            $request->get('title')
            ));

        //Files not transfered
        if (!$transferedFile) {
            return response()->json(['message' => "File not transfered!"], 400);
        }

        //Return Response
        return $this->response->item($transferedFile, new FileListTransformer);
    }

    /**
     * Scheule a File Transfer across Providers
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function scheduleTransfer(Request $request, $account_id)
    {

        if (!$request->has('file') || !$request->has('account') || !$request->has('scheduled_at')) {
            return response()->json(['message' => "File, schedule time or account was not specified!"], 400);
        }

        //File
        $file = $request->get('file');
        //Scheduled At
        $scheduled_at = $request->get('scheduled_at');
        //New Account ID
        $newAccountID = $request->get('account');

        //Current User
        $user = Auth::user();
        //Account
        $account = $user->accounts()->findOrFail($account_id);
        //New Account
        $newAccount = $user->accounts()->findOrFail($newAccountID);

        //Schedule Transfer
        $scheduleTransfer = dispatch(new ScheduleTransferCommand(
            $user,
            $account,
            $newAccount,
            $file,
            $request->get('scheduled_at'),
            $request->get('location')
            ));

        //Transfer not scheduled
        if (!$scheduleTransfer) {
            return response()->json(['message' => "Cannot schedule transfer!"], 400);
        }

        //Return Response
        return response()->json(['message' => 'Transfer Scheduled!']);
    }

}
