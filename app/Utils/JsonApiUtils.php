<?php namespace App\Utils;

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
     * creates a resource object for JSON API formatted response
     * http://jsonapi.org/format/#document-resource-objects
     *
     * @param array $data
     * @param $type
     * @param $link_self
     * @return array
     */
    public static function makeResourceObject($data, $type, $link_self)
    {
        return [
            'id'            => strval($data['id']),
            'type'          => $type,
            'attributes'    => $data,
            'links' => [
                'self' => $link_self
            ],
        ];
    }

    /**
     * creates a pagination links object for JSON API formatted response
     * http://jsonapi.org/format/#fetching-pagination
     *
     * @param LengthAwarePaginator $paginator
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
     * creates an error object for JSON API formatted response
     * http://jsonapi.org/format/#error-objects
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
