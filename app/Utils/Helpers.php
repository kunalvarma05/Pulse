<?php
namespace Pulse\Utils;

class Helpers {

    public static function defaultAccountPicture()
    {
        return asset("images/user.png");
    }

    public static function defaultProviderPicture($provider_alias)
    {
        return asset("images/providers/{$provider_alias}.png");
    }

    public static function defaultUserPicture()
    {
        return asset("images/user.png");
    }

}