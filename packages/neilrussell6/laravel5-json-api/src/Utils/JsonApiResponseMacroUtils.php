<?php namespace NeilRussell6\Laravel5JsonApi\Utils;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class JsonApiResponseMacroUtils
 * @package App\Utils
 *
 * A utility class used by response macros to generates JSON API responses
 */
class JsonApiResponseMacroUtils
{
    /**
     * make collection response
     *
     * @param Collection $collection
     * @param $model
     * @param $base_url
     * @param bool $include_resource_object_links
     * @param bool $is_minimal (restricts the results to only type & id)
     * @return array
     */
    public static function makeCollectionResponse (Collection $collection, $model, $base_url, $include_resource_object_links = true, $is_minimal = false)
    {
        $include_relationships = false;

        $result = [
            'links' => JsonApiUtils::makeTopLevelLinksObject($base_url),
            'data' => $collection->map(function($item) use ($model, $base_url, $include_relationships, $is_minimal, $include_resource_object_links) {

                $links = $include_resource_object_links ? JsonApiUtils::makeResourceObjectLinksObject($base_url, $item['id']) : null;
                return JsonApiUtils::makeResourceObject($item->toArray(), $model, $base_url, $links, $include_relationships, $is_minimal);
            })
        ];

        return $result;
    }

    /**
     * make item response
     *
     * @param $data
     * @param $model
     * @param $base_url
     * @param $include_resource_object_links
     * @param bool $is_minimal (restricts the results to only type & id)
     * @return array
     */
    public static function makeItemResponse ($data, $model, $base_url, $include_resource_object_links = false, $is_minimal = false)
    {
        $top_level_links = JsonApiUtils::makeTopLevelLinksObject($base_url, $data['id']);
        $resource_object_links = $include_resource_object_links ? JsonApiUtils::makeResourceObjectLinksObject($base_url, $data['id']) : null;
        $include_relationships = preg_match('/\/\w+\/\d+\/\w+$/', $base_url) === 0 && preg_match('/\/\w+\/\d+\/relationships\/\w+$/', $base_url) === 0; // don't include relationships for sub resource or relationships request

        return [
            'links' => $top_level_links,
            'data' => is_null($data) ? $data : JsonApiUtils::makeResourceObject($data, $model, $top_level_links['self'], $resource_object_links, $include_relationships, $is_minimal)
        ];
    }

    /**
     * make pagination response
     *
     * @param LengthAwarePaginator $paginator
     * @param $model
     * @param $full_base_url
     * @param $base_url
     * @param $query_params
     * @return array
     */
    public static function makePaginationResponse (LengthAwarePaginator $paginator, $model, $full_base_url, $base_url, $query_params)
    {
        return [
            'links' => JsonApiUtils::makeTopLevelPaginationLinksObject($paginator, $full_base_url, $base_url, $query_params),
            'meta' => JsonApiUtils::makeTopLevelPaginationMetaObject($paginator),
            'data' => $paginator->getCollection()->map(function($item) use ($model, $base_url) {
                $include_relationships = false;
                $links = JsonApiUtils::makeResourceObjectLinksObject($base_url, $item['id']);
                return JsonApiUtils::makeResourceObject($item, $model, $base_url, $links, $include_relationships);
            })
        ];
    }
}
