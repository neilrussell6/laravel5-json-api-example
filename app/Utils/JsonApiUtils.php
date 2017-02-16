<?php namespace App\Utils;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class JsonApiUtils
 * @package App\Utils
 *
 * A utility class that generates various parts of JSON API response
 */
class JsonApiUtils
{
    /**
     * creates a relationship object for JSON API formatted response
     *
     * @param $name
     * @param $link_self
     * @return array
     */
    public static function makeRelationshipObject($name, $link_self) {
        return [
            'links' => [
                'self' => "{$link_self}/relationships/{$name}",
                'related' => "{$link_self}/{$name}",
            ]
        ];
    }

    /**
     * creates a resource object for JSON API formatted response
     * http://jsonapi.org/format/#document-resource-objects
     *
     * @param array $data
     * @param $model
     * @param $link_self
     * @return array
     */
    public static function makeResourceObject($data, $model, $link_self, $include_relationships = true)
    {
        $collection = new Collection($data);

        // don't include type, id or foreign keys in attributes
        $filtered_collection = $collection->filter(function($item, $key) {
            return !in_array($key, ['id', 'type']) && preg_match('/(.*?)\_id$/', $key) !== 1;
        });

        // relationships
        $relationships = [];
        if ($include_relationships && property_exists($model, 'default_includes') && !empty($model->default_includes)) {

            // build relationships objects
            $relationships = array_reduce($model->default_includes, function ($carry, $default_include) use ($link_self) {
                return array_merge($carry, [ $default_include => self::makeRelationshipObject($default_include, $link_self) ]);
            }, []);

            // get relationship data
//            array_map(function ($default_include) use ($data, $model) {
//                if (method_exists($model, $default_include)) {
//                    $relationship_data = $data->$default_include()->get()->toArray();
//                    var_dump($relationship_data);
//                }
//            }, $model->default_includes);
        }

        $result = [
            'id'            => strval($data['id']),
            'type'          => $model->type,
            'attributes'    => $filtered_collection->toArray(),
            'links' => [
                'self' => $link_self
            ],
        ];

        if (!empty($relationships)) {
            $result = array_merge($result, [ 'relationships' => $relationships ]);
        }

        return $result;
    }

    /**
     * creates a pagination links object for JSON API formatted response
     * http://jsonapi.org/format/#fetching-pagination
     *
     * @param LengthAwarePaginator $paginator
     * @param $base_url
     * @param $query_params
     * @return array
     */
    public static function makePaginationLinksObject(LengthAwarePaginator $paginator, $base_url, $query_params)
    {
        $indices = [
            'current'   => $paginator->currentPage(),
            'first'     => 1,
            'last'      => $paginator->lastPage(),
            'next'      => $paginator->hasMorePages() ? $paginator->currentPage() + 1 : null,
            'prev'      => !$paginator->onFirstPage() ? $paginator->currentPage() - 1 : null,
        ];

        $page_query = array_key_exists('page', $query_params) && !is_null($query_params['page']) ? $query_params['page'] : [];

        $query_params = [
            'current'   => !is_null($indices['current']) ? http_build_query(['page' => array_replace_recursive($page_query, ['offset' => $indices['current']])]) : null,
            'first'     => !is_null($indices['first']) ? http_build_query(['page' => array_replace_recursive($page_query, ['offset' => $indices['first']])]) : null,
            'last'      => !is_null($indices['last']) ? http_build_query(['page' => array_replace_recursive($page_query, ['offset' => $indices['last']])]) : null,
            'next'      => !is_null($indices['next']) ? http_build_query(['page' => array_replace_recursive($page_query, ['offset' => $indices['next']])]) : null,
            'prev'      => !is_null($indices['prev']) ? http_build_query(['page' => array_replace_recursive($page_query, ['offset' => $indices['prev']])]) : null,
        ];

        return [
            'first' => !is_null($indices['first']) ? "{$base_url}?{$query_params['first']}" : null,
            'last'  => !is_null($indices['last']) ? "{$base_url}?{$query_params['last']}" : null,
            'next'  => !is_null($indices['next']) ? "{$base_url}?{$query_params['next']}" : null,
            'prev'  => !is_null($indices['prev']) ? "{$base_url}?{$query_params['prev']}" : null,
        ];
    }

    /**
     * creates a pagination meta object for JSON API formatted response
     * http://jsonapi.org/format/#fetching-pagination
     *
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    public static function makePaginationMetaObject(LengthAwarePaginator $paginator)
    {
        return [
            'pagination' => [
                'count'         => $paginator->count(),
                'limit'         => $paginator->perPage(),
                'offset'        => $paginator->currentPage(),
                'total_items'   => $paginator->total(),
                'total_pages'   => $paginator->lastPage(),
            ]
        ];
    }

    /**
     * creates an array of error objects error object for JSON API formatted response
     * http://jsonapi.org/format/#error-objects
     *
     * @param $error_messages
     * @param $http_code
     * @return array
     */
    public static function makeErrorObjects(array $error_messages, $http_code = 422)
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

    /**
     * creates an array of error objects from attribute validation errors for JSON API formatted response
     * http://jsonapi.org/format/#error-objects
     *
     * @param $attribute_validation_error_messages
     * @param $http_code
     * @return array
     */
    public static function makeErrorObjectsFromAttributeValidationErrors(array $attribute_validation_error_messages, $http_code = 422)
    {
        $error_messages = array_map(function($field) use ($attribute_validation_error_messages) {
            return [
                'detail'    => $attribute_validation_error_messages[ $field ][0],
                'source'    => [
                    'pointer' => "/data/attributes/{$field}"
                ],
                'title'     => "Invalid Attribute"
            ];
        }, array_keys($attribute_validation_error_messages));

        return self::makeErrorObjects($error_messages, $http_code);
    }

    /**
     * creates the top level object for JSON API formatted response
     * http://jsonapi.org/format/#document-top-level
     *
     * @param array $response
     * @param $self_link
     * @return mixed
     */
    public static function makeResponseObject(array $response, $self_link = null)
    {
        $default_content = [
            'jsonapi' => [
                'version' => '1.0'
            ]
        ];

        if (!is_null($self_link)) {
            $default_content['links'] = [
                'self' => $self_link
            ];
        }

        return array_merge_recursive($default_content, $response);
    }
}
