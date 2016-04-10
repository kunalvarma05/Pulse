<?php
namespace Pulse\Utils;

class Helpers {

    public static function defaultAccountPicture($provider_alias)
    {
        return asset("images/providers/{$provider_alias}.png");
    }

    public static function defaultProviderPicture($provider_alias)
    {
        return asset("images/providers/{$provider_alias}.png");
    }

}