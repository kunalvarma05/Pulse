<?php
namespace Pulse\Api\Transformers;

use Pulse\Utils\Helpers;
use League\Fractal\TransformerAbstract;
use Pulse\Services\Manager\File\FileInterface;

class FileListTransformer
{

    public function transform(FileInterface $file)
    {
        return [
            "id" => $file->getId(),
            "title" => $file->getTitle(),
            "path" => $file->getPath(),
            "modified" => $file->getModified(),
            "size" => Helpers::formatBytes($file->getSize()),
            "isFolder" => $file->isFolder(),
            "thumbnailUrl" => $file->getThumbnailURL(),
            "url" => $file->getURL(),
            "mimeType" => $file->getMimeType(),
            "downloadUrl" => $file->getDownloadURL(),
            "icon" => $file->getIcon(),
            "owners" => $file->getOwners()
        ];
    }
}