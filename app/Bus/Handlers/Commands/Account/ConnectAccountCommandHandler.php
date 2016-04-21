<?php
namespace Pulse\Bus\Handlers\Commands\Account;

use Pulse\Models\Account;
use Pulse\Bus\Commands\Account\CreateAccountCommand;
use Pulse\Bus\Commands\Account\ConnectAccountCommand;
use Pulse\Bus\Commands\Provider\FetchAccountInfoCommand;
use Pulse\Bus\Commands\Provider\GenerateAccessTokenCommand;

class ConnectAccountCommandHandler
{

    /**
     * Handle ConnectAccountCommand
     * @param  ConnectAccountCommand $command
     * @return array ['error' => true|false, 'message' => null|string, 'data' => null|Pulse\Models\Account]
     */
    public function handle(ConnectAccountCommand $command)
    {
        $response = ['error' => false, "message" => null, 'data' => null];

        //Fetch Access Token
        $access_token = $this->getAccessToken($command->code, $command->state, $command->provider);

        //Fetch Account Info
        $accountInfo = $this->fetchAccountInfo($command->provider, $access_token);

        //Cloud Account ID
        $uid = $accountInfo->getId();

        //Check if account is already connected
        $exists = Account::where('uid', '=', $uid)->count();

        //If account is already connected
        if ($exists) {
            $response['error'] = true;
            $response['message'] = "Account already connected";

            return $response;
        }

        //Create the Account
        $account = $this->createAccount(
            $command->user,
            $command->provider,
            $command->name,
            $uid,
            $access_token
            );

        //If account wasn't created
        if (!$account) {
            $response['error'] = true;
            $response['message'] = "Cannot create account!";

            return $response;
        }

        //Account was created
        $response['error'] = false;
        $response['data'] = $account;

        return $response;
    }

    /**
     * Get Access Token
     * @param  string $code     Authorization Code
     * @param  string $state    CSRF State
     * @param  Pulse\Models\Provider $provider
     * @return string           Generated Access Token
     */
    protected function getAccessToken($code, $state, $provider)
    {
        //Dispatch GenerateAccessTokenCommand
        return dispatch(new GenerateAccessTokenCommand(
            $code,
            $state,
            $provider
            ));
    }

    /**
     * Fetch Account Info
     * @param  Pulse\Models\Provider $provider
     * @param  string $access_token
     * @return Pulse\Services\Identity\Account\AccountInterface
     */
    protected function fetchAccountInfo($provider, $access_token)
    {
        //Dispatch FetchAccountInfoCommand
        return dispatch(new FetchAccountInfoCommand($provider, $access_token));
    }

    /**
     * Create Account
     * @param  Pulse\Models\User $user
     * @param  Pulse\Models\Provider $provider
     * @param  string $name         Account Name
     * @param  string $uid          Cloud Account ID
     * @param  string $access_token Access Token
     * @return false|Pulse\Models\Account
     */
    protected function createAccount($user, $provider, $name, $uid, $access_token)
    {
        //Dispatch CreateAccountCommand
        return dispatch(new CreateAccountCommand(
            $user,
            $provider,
            $name,
            $uid,
            $access_token
            ));
    }
}
