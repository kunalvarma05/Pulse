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
        'provider'
    ];

    public function transform(Account $account)
    {
        return [
        "id" => (int) $account->id,
        "uid" => (int) $account->uid,
        "name" => $account->name,
        "picture" => is_null($account->picture) ? Helpers::defaultAccountPicture($account->provider_id) : $account->picture,
        "provider_id" => (int) $account->provider_id,
        "user_id" => (int) $account->user_id,
        "created_at" => $account->created_at->diffForHumans(),
        "updated_at" => $account->updated_at->diffForHumans()
        ];
    }

    /**
     * Include Provider
     *
     * @return League\Fractal\ItemResource
     */
    public function includeProvider(Account $account)
    {
        return $this->item($account->provider, function($provider){
            return [
            'id' => $provider->id,
            'title' => $provider->title
            ];
        });
    }
}