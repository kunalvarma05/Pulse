<?php
namespace Pulse\Api\Transformers;

use Pulse\Utils\Helpers;
use Pulse\Models\Provider;
use League\Fractal\TransformerAbstract;

class ProviderTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    public function transform(Provider $provider)
    {
        return [
            "id" => (int) $provider->id,
            "title" => $provider->title,
            "description" => $provider->description,
            "link" => $provider->link,
            "alias" => $provider->alias,
            "picture" => is_null($provider->picture) ? Helpers::defaultProviderPicture($provider->alias) : $provider->picture,
            "created_at" => $provider->created_at ? $provider->created_at->diffForHumans() : "",
            "updated_at" => $provider->updated_at ? $provider->updated_at->diffForHumans() : ""
        ];
    }
}