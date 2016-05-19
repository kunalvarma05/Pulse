<?php
namespace Pulse\Api\Transformers;

use Pulse\Utils\Helpers;
use Pulse\Models\Account;
use League\Fractal\TransformerAbstract;

class AccountTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'provider',
        'user'
    ];

    public function transform(Account $account)
    {
        return [
            "id" => (int) $account->id,
            "uid" => (string) $account->uid,
            "name" => $account->name,
            "picture" => is_null($account->picture) ? Helpers::defaultAccountPicture($account->provider->alias) : $account->picture,
            "provider_id" => (int) $account->provider_id,
            "user_id" => (int) $account->user_id,
            "created_at" => $account->created_at ? $account->created_at->diffForHumans() : "",
            "updated_at" => $account->updated_at ? $account->updated_at->diffForHumans() : ""
        ];
    }

    /**
     * Include Provider
     *
     * @return League\Fractal\Resource\Item
     */
    public function includeProvider(Account $account)
    {
        return $this->item($account->provider, new ProviderTransformer);
    }

    /**
     * Include User
     *
     * @return League\Fractal\Resource\Item
     */
    public function includeUser(Account $account)
    {
        return $this->item($account->user, new UserTransformer);
    }
}
