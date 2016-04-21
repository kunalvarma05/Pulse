<?php
namespace Pulse\Api\Transformers;

use Pulse\Utils\Helpers;
use Pulse\Models\Transfer;
use League\Fractal\TransformerAbstract;

class TransferTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'file',
        'to_account',
        'from_account',
        'activities',
        'scheduled_transfer'
    ];

    public function transform(Transfer $transfer)
    {
        return [
            "id" => (int) $transfer->id,
            "type" => $transfer->type,
            "file_id" => (int) $transfer->file_id,
            "to_account_id" => (int) $transfer->to_account_id,
            "from_account_id" => (int) $transfer->from_account_id,
            "created_at" => $transfer->created_at ? $transfer->created_at->diffForHumans() : "",
            "updated_at" => $transfer->updated_at ? $transfer->updated_at->diffForHumans() : ""
        ];
    }

    /**
     * Include Account
     *
     * @return League\Fractal\Resource\Item
     */
    public function includeFromAccount(Transfer $transfer)
    {
        return $this->item($transfer->fromAccount, new AccountTransformer);
    }

    /**
     * Include Account
     *
     * @return League\Fractal\Resource\Item
     */
    public function includeToAccount(Transfer $transfer)
    {
        return $this->item($transfer->toAccount, new AccountTransformer);
    }

    /**
     * Include File
     *
     * @return League\Fractal\Resource\Item
     */
    public function includeFile(Transfer $transfer)
    {
        return $this->item($transfer->file, new FileTransformer);
    }

    /**
     * Include Scheduled Transfer
     *
     * @return League\Fractal\Resource\Item
     */
    public function includeScheduledTransfer(Transfer $transfer)
    {
        return $this->item($transfer->scheduledTransfer, new ScheduledTransferTransformer);
    }

    /**
     * Include Activity
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeActivities(Transfer $transfer)
    {
        return $this->collection($transfer->activities, new ActivityTransformer);
    }
}
