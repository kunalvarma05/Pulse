<?php
namespace Pulse\Utils;

class Helpers {

    public static function defaultAccountPicture($provider_alias)
    {
        return asset("images/providers/{$provider_alias}.png");
    }

}