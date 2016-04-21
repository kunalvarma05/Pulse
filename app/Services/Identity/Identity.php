<?php
namespace Pulse\Services\Identity;

use Pulse\Services\Identity\Adapters\AdapterInterface;

class Identity
{
    /**
     * Identity Adapter
     * @var Pulse\Services\Identity\Adapters\AdapterInterface;
     */
    private $adapter;

    /**
     * Constructor
     * @param AdapterInterface $adapter Identity Adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get Identity Adapter
     * @return Pulse\Services\Identity\Adapters\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Set Identity Adapter
     * @return Pulse\Services\Identity\Adapters\AdapterInterface
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get Account
     * @return Pulse\Serivces\Identity\Account\AccountInterface
     */
    public function getAccount()
    {
        return $this
        ->getAdapter()
        ->getAccount();
    }
}
