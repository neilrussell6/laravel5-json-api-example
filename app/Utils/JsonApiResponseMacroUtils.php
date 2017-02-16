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
     * @param $model
     * @param $full_url
     * @return array
     */
    public static function makeItemResponse ($data, $model, $full_url)
    {
        // add id to self link if it is not already there
        $url_parts = parse_url($full_url);
        $url_parts['path'] = UrlUtils::containsId($url_parts['path']) ? $url_parts['path'] : "{$url_parts['path']}/{$data['id']}";
        $link_self = http_build_url($url_parts);

        return [
            'data' => JsonApiUtils::makeResourceObject($data, $model, $link_self)
        ];
    }

    /**
     * make pagination response
     *
     * @param LengthAwarePaginator $paginator
     * @param $model
     * @param $base_url
     * @param $query_params
     * @return array
     */
    public static function makePaginationResponse (LengthAwarePaginator $paginator, $model, $base_url, $query_params)
    {
        return [
            'links' => JsonApiUtils::makePaginationLinksObject($paginator, $base_url, $query_params),
            'meta' => JsonApiUtils::makePaginationMetaObject($paginator),
            'data' => array_map(function($item) use ($model, $base_url) {
                $include_relationships = false;
                return JsonApiUtils::makeResourceObject($item, $model, "{$base_url}/{$item['id']}", $include_relationships);
            }, $paginator->toArray()['data'])
        ];
    }
}
