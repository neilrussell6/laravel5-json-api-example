<?php namespace App\Utils;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class JsonApiUtils
 * @package App\Utils
 *
 * A service that transforms various parts of JSON API responses
 */
class JsonApiUtils
{
    /**
     * transforms errors for JSON API formatted response
     *
     * @param $error_messages
     * @param $http_code
     * @return array
     */
    public static function makeErrorObject(array $error_messages, $http_code = 422)
    {
        return array_map(function($message) use ($http_code) {

            // members with default fallback values
            $result['status'] = array_key_exists('status', $message) ? $message['status'] : $http_code;

            // members only included if value provided
            if (array_key_exists('id', $message)) { $result['id'] = $message['id']; }
            if (array_key_exists('about', $message)) { $result['about'] = $message['about']; }
            if (array_key_exists('code', $message)) { $result['code'] = $message['code']; }
            if (array_key_exists('detail', $message)) { $result['detail'] = $message['detail']; }
            if (array_key_exists('links', $message)) { $result['links'] = $message['links']; }
            if (array_key_exists('meta', $message)) { $result['meta'] = $message['meta']; }
            if (array_key_exists('pointer', $message)) { $result['pointer'] = $message['pointer']; }
            if (array_key_exists('parameter', $message)) { $result['parameter'] = $message['parameter']; }
            if (array_key_exists('source', $message)) { $result['source'] = $message['source']; }
            if (array_key_exists('title', $message)) { $result['title'] = $message['title']; }

            return $result;
        }, $error_messages);
    }
}
