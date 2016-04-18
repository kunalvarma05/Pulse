<?php
namespace Pulse\Api\Transformers;

use Pulse\Utils\Helpers;
use League\Fractal\TransformerAbstract;
use Pulse\Services\Manager\Quota\QuotaInterface;

class QuotaTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    public function transform(QuotaInterface $quota)
    {
        return [
            'space_alloted' => $quota->getSpaceAlloted(true),
            'space_used' => $quota->getSpaceUsed(true),
            'space_remaining' => $quota->getSpaceRemaining(true)
        ];
    }
}