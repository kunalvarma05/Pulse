<?php
namespace Pulse\Bus\Handlers\Commands\Provider;

use Pulse\Services\Authorization\ResolverInterface;
use Pulse\Bus\Commands\Provider\GenerateAccessTokenCommand;

class GenerateAccessTokenCommandHandler
{

    /**
     * Handle the Generate Access Token Command
     *
     * @param  Pulse\Bus\Commands\Provider\GenerateAccessTokenCommand $command
     * @param  Pulse\Services\Authorization\ResolverInterface    $resolver
     * @return string Generated Access Token
     */
    public function handle(GenerateAccessTokenCommand $command)
    {
        //Authorization Provider Resolver
        $resolver = app('pulse.auth.resolver');

        //Resolve Auth Provider
        $authProvider = $resolver->resolve(strtolower($command->provider->alias));

        //Generate Access Token
        $access_token = $authProvider->getAccessToken($command->code, $command->state);

        //Return the generated token
        return $access_token;
    }

}