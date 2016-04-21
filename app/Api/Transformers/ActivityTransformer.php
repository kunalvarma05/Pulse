<?php
namespace Pulse\Api\Transformers;

use Pulse\Utils\Helpers;
use Pulse\Models\Action;
use Pulse\Models\Transfer;
use Pulse\Models\Activity;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user',
        'transaction'
    ];

    public function transform(Activity $activity)
    {
        return [
            "id" => (int) $activity->id,
            "status" => $activity->status,
            "user_id" => (int) $activity->user_id,
            "transaction_type" => $activity->transaction_type,
            "transaction_id" => (int) $activity->transaction_id,
            "created_at" => $activity->created_at ? $activity->created_at->diffForHumans() : "",
            "updated_at" => $activity->updated_at ? $activity->updated_at->diffForHumans() : ""
        ];
    }

    /**
     * Include User
     *
     * @return League\Fractal\Resource\Item
     */
    public function includeUser(Activity $activity)
    {
        return $this->item($activity->user, new UserTransformer);
    }

    /**
     * Include Transaction
     *
     * @return League\Fractal\Resource\Item
     */
    public function includeTransaction(Activity $activity)
    {
        if ($activity->transaction_type === Action::class) {
            return $this->item($activity->transaction, new ActionTransformer);
        }

        return $this->item($activity->transaction, new TransferTransformer);
    }
}
