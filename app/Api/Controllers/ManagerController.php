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
use Pulse\Bus\Commands\Manager\UploadFileCommand;
use Pulse\Bus\Commands\Manager\GetFileInfoCommand;
use Pulse\Bus\Commands\Manager\CreateFolderCommand;
use Pulse\Bus\Commands\Manager\GetDownloadLinkCommand;

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
     * Get File Info
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function getFileInfo(Request $request, $account_id)
    {
        if(!$request->has('file')) {
            return response()->json(['error' => 'no_file_specified', 'message' => "No file was specified!"], 200);
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
        if(!$fileInfo) {
            return response()->json(['error' => 'file_not_found', 'message' => "File not found!"], 200);
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

        //Copy File
        $fileCopy = dispatch(new CopyCommand(
            $user,
            $account,
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

        //Move File
        $fileMove = dispatch(new MoveCommand(
            $user,
            $account,
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

        $file = $request->get('file');

        //Delete File
        $fileDelete = dispatch(new DeleteCommand(
            $user,
            $account,
            $file
            ));

        //Files not deleted
        if(!$fileDelete) {
            return response()->json(['error' => 'file_not_deleted', 'message' => "Cannot delete file!"], 200);
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
        if(!$request->has('title')) {
            return response()->json(['error' => 'no_title_specified', 'message' => "No title was specified!"], 200);
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
        if(!$folder) {
            return response()->json(['error' => 'folder_not_created', 'message' => "Cannot create folder!"], 200);
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
        if(!$request->has('file')) {
            return response()->json(['error' => 'no_file_specified', 'message' => "No file was specified!"], 200);
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
        if(!$downloadLink) {
            return response()->json(['error' => 'download_link_unavailable', 'message' => "Download link unavailable!"], 200);
        }

        //Return Response
        return $this->response->array(['link' => $downloadLink]);
    }

    /**
     * Upload File
     * @param  Request $request
     * @param  int  $account_id Account ID
     * @return Response
     */
    public function uploadFile(Request $request, $account_id)
    {
        if(!$request->hasFile('file')) {
            return response()->json(['error' => 'no_file_specified', 'message' => "No file was specified!"], 200);
        }

        //File
        $file = $request->file('file');

        //Invalid File
        if (!$file->isValid()) {
            return response()->json(['error' => 'invalid_file', 'message' => "Please upload a valid file!"], 200);
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
        if(!$uploadedFile) {
            return response()->json(['error' => 'file_not_uploaded', 'message' => "File not uploaded!"], 200);
        }

        //Return Response
        return $this->response->item($uploadedFile, new FileListTransformer);
    }

}