<?php
namespace Pulse\Services\Authorization;

interface AuthorizationInterface
{

    /**
     * Get Authorization URL
     *
     * @param  array $data Additional Data
     * @return string
     */
    public function getAuthorizationUrl(array $data = array());

    /**
     * Get Access Token
     * @param  string $code  Authorization Code
     * @param  string $state CSRF State
     * @param  array  $data  Additional Data
     * @return string        Access Token
     */
    public function getAccessToken($code, $state, array $data = array());

    /**
     * Refresh Access Token
     * @param  string $access_token
     * @return string
     */
    public function refreshAccessToken($access_token);
}
