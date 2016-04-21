<?php
namespace Pulse\Services\Authorization;

use Pulse\Services\Authorization\Adapters\AdapterInterface;

class Authorization implements AuthorizationInterface
{

    /**
     * Authorization Adapter
     * @var Pulse\Services\Authorization\Adapters\AdapterInterface
     */
    private $adapter;

    /**
     * Constructor
     * @param AdapterInterface $adapter Authorization Adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->setAdapter($adapter);
    }

    /**
     * Set the Authorization Adapter
     * @param Pulse\Services\Authorization\Adapters\AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get the Authorization Adapter
     * @return Pulse\Services\Authorization\Adapters\AdapterInterface $adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Get Authorization URL
     *
     * @param  array $data Additional Data
     * @return string
     */
    public function getAuthorizationUrl(array $data = array())
    {
        return $this
        ->getAdapter()
        ->getAuthorizationUrl($data);
    }

    /**
     * Get Access Token
     * @param  string $code  Authorization Code
     * @param  string $state CSRF State
     * @param  array  $data  Additional Data
     * @return string        Access Token
     */
    public function getAccessToken($code, $state, array $data = array())
    {
        return $this
        ->getAdapter()
        ->getAccessToken($code, $state, $data);
    }

    /**
     * Refresh Access Token
     * @param  string $access_token
     * @return string
     */
    public function refreshAccessToken($access_token)
    {
        return $this
        ->getAdapter()
        ->refreshAccessToken($access_token);
    }
}
