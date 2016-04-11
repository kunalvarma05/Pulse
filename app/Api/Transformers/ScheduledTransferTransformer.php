<?php
namespace Pulse\Api\Transformers;

use Pulse\Utils\Helpers;
use Pulse\Models\Action;
use Pulse\Models\Transfer;
use Pulse\Models\ScheduledTransfer;
use League\Fractal\TransformerAbstract;

class ScheduledTransferTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user',
        'transfer'
    ];

    public function transform(ScheduledTransfer $scheduledTransfer)
    {
        return [
            "id" => (int) $scheduledTransfer->id,
            "status" => $scheduledTransfer->status,
            "user_id" => (int) $scheduledTransfer->user_id,
            "transfer_id" => (int) $scheduledTransfer->transfer_id,
            "created_at" => $scheduledTransfer->created_at ? $scheduledTransfer->created_at->diffForHumans() : "",
            "updated_at" => $scheduledTransfer->updated_at ? $scheduledTransfer->updated_at->diffForHumans() : ""
        ];
    }

    /**
     * Include User
     *
     * @return League\Fractal\Resource\Item
     */
    public function includeUser(ScheduledTransfer $scheduledTransfer)
    {
        return $this->item($scheduledTransfer->user, new UserTransformer);
    }

    /**
     * Include Tranfer
     *
     * @return League\Fractal\Resource\Item
     */
    public function includeTranfer(ScheduledTransfer $scheduledTransfer)
    {
        return $this->item($scheduledTransfer->transfer, new TranferTransformer);
    }

}