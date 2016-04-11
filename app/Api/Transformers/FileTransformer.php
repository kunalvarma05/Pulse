<?php
namespace Pulse\Api\Transformers;

use Pulse\Models\File;
use Pulse\Utils\Helpers;
use League\Fractal\TransformerAbstract;

class FileTransformer
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'account',
        'actions',
        'transfers'
    ];

    public function transform(File $file)
    {
        return [
            "id" => (int) $file->id,
            "key" => $file->key,
            "account_id" => (int) $file->account_id,
            "is_encrypted" => (bool) $file->is_encrypted,
            "created_at" => $file->created_at ? $file->created_at->diffForHumans() : "",
            "updated_at" => $file->updated_at ? $file->updated_at->diffForHumans() : ""
        ];
    }

    /**
     * Include Account
     *
     * @return League\Fractal\Resource\Item
     */
    public function includeAccount(File $file)
    {
        return $this->item($file->account, new AccountTransformer);
    }

    /**
     * Include Action
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeActions(File $file)
    {
        return $this->collection($file->actions, new ActionTransformer);
    }

    /**
     * Include Transfer
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeTransfers(File $file)
    {
        return $this->collection($file->transfers, new TransferTransformer);
    }
}