<?php
namespace Pulse\Api\Transformers;

use Pulse\Utils\Helpers;
use Pulse\Models\Action;
use League\Fractal\TransformerAbstract;

class ActionTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'file',
        'account',
        'activities'
    ];

    public function transform(Action $action)
    {
        return [
            "id" => (int) $action->id,
            "type" => $action->type,
            "file_id" => (int) $action->file_id,
            "account_id" => (int) $action->account_id,
            "created_at" => $action->created_at ? $action->created_at->diffForHumans() : "",
            "updated_at" => $action->updated_at ? $action->updated_at->diffForHumans() : ""
        ];
    }

    /**
     * Include Account
     *
     * @return League\Fractal\Resource\Item
     */
    public function includeAccount(Action $action)
    {
        return $this->item($action->account, new AccountTransformer);
    }

    /**
     * Include File
     *
     * @return League\Fractal\Resource\Item
     */
    public function includeFile(Action $action)
    {
        return $this->item($action->file, new FileTransformer);
    }

    /**
     * Include Activity
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeActivities(Action $action)
    {
        return $this->collection($action->activities, new ActivityTransformer);
    }
}
