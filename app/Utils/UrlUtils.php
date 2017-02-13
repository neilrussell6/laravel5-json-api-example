<?php namespace App\Utils;

/**
 * Class UrlUtils
 * @package App\Utils
 *
 * A utility class for working with URLS
 */
class UrlUtils
{
    /**
     * checks if given url contains an id
     *
     * @param $url
     * @return bool
     */
    public static function containsId($url)
    {
        return preg_match('/\/(?=[^\/.]*$)\d+/', $url) ? true : false;
    }
}
