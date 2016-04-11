<?php
namespace Pulse\Api\Transformers;

use Pulse\Models\User;
use Pulse\Utils\Helpers;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'accounts',
        'activities',
        'scheduled_transfers'
    ];

    public function transform(User $user)
    {
        return [
            "id" => (int) $user->id,
            "name" => $user->name,
            "username" => $user->username,
            "picture" => is_null($user->picture) ? Helpers::defaultUserPicture() : $user->picture,
            "created_at" => $user->created_at ? $user->created_at->diffForHumans() : "",
            "updated_at" => $user->updated_at ? $user->updated_at->diffForHumans() : ""
        ];
    }

    /**
     * Include Accounts
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeAccounts(User $user)
    {
        return $this->collection($user->accounts, new AccountTransformer);
    }

    /**
     * Include Activites
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeActivites(User $user)
    {
        return $this->collection($user->activities, new ActivityTransformer);
    }

    /**
     * Include Scheduled Transfer
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeScheduledTransfers(User $user)
    {
        return $this->collection($user->scheduledTransfers, new ScheduledTransferTransformer);
    }

}