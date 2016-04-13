<?php
namespace Pulse\Bus\Handlers\Commands\Provider;

use Pulse\Services\Authorization\AuthFactory;
use Pulse\Bus\Commands\Provider\GenerateAccessTokenCommand;

class GenerateAccessTokenCommandHandler
{

    /**
     * Handle the Generate Access Token Command
     *
     * @param  Pulse\Bus\Commands\Provider\GenerateAccessTokenCommand $command
     * @return string Generated Access Token
     */
    public function handle(GenerateAccessTokenCommand $command)
    {
        //Resolve Authorization Service
        $authorization = AuthFactory::create(strtolower($command->provider->alias));

        //Generate Access Token
        $access_token = $authorization->getAccessToken($command->code, $command->state);

        //Return the generated token
        return $access_token;
    }

}