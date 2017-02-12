<?php namespace App\Utils;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class JsonApiResponseMacroUtils
 * @package App\Utils
 *
 * A utility class used by response macros to generates JSON API responses
 */
class JsonApiResponseMacroUtils
{
    /**
     * make item response
     *
     * @param $data
     * @param $type
     * @param $full_url
     * @return array
     */
    public static function makeItemResponse ($data, $type, $full_url)
    {
        return [
            'data' => JsonApiUtils::makeResourceObject($data, $type, $full_url)
        ];
    }

    /**
     * make pagination response
     *
     * @param LengthAwarePaginator $paginator
     * @param $type
     * @param $base_url
     * @param $query_params
     * @return array
     */
    public static function makePaginationResponse (LengthAwarePaginator $paginator, $type, $base_url, $query_params)
    {
        return [
            'links' => JsonApiUtils::makePaginationLinksObject($paginator, $base_url, $query_params),
            'meta' => JsonApiUtils::makePaginationMetaObject($paginator),
            'data' => array_map(function($item) use ($type, $base_url) {
                return JsonApiUtils::makeResourceObject($item, $type, "{$base_url}/{$item['id']}");
            }, $paginator->toArray()['data'])
        ];
    }
}
