<?php
namespace Pulse\Utils;

class Helpers
{

    /**
     * Generate a querystring url for the application.
     *
     * Assumes that you want a URL with a querystring rather than route params
     * (which is what the default url() helper does)
     *
     * @param  string  $path
     * @param  mixed   $qs
     * @param  bool    $secure
     * @return string
     */
    public static function url($path = null, $qs = array(), $secure = null)
    {
        $url = app('url')->to($path, $secure);
        if (count($qs)){

            foreach($qs as $key => $value){
                $qs[$key] = sprintf('%s=%s',$key, urlencode($value));
            }
            $url = sprintf('%s?%s', $url, implode('&', $qs));
        }
        return $url;
    }

    public static function defaultAccountPicture($provider_alias)
    {
        return static::defaultProviderPicture($provider_alias);
    }

    public static function defaultProviderPicture($provider_alias)
    {
        return asset("images/providers/{$provider_alias}.png");
    }

    public static function defaultUserPicture()
    {
        return asset("images/user.png");
    }

    public static function getFileIcon($mime)
    {
        $list = config('fileicons.list');
        $icon = array_key_exists($mime, $list) ? $list[$mime] : $list['default'];
        return $icon;
    }

    /**
     * Format Bytes to human readable size
     * @param  int  $bytes     Filesize in Bytes
     * @param  integer $precision Return value precision
     * @return string             Human readable filesize
     */
    public static function formatBytes($bytes, $precision = 2)
    {
        $bytes = $bytes ? $bytes : 0;
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
