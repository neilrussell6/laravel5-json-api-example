<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(ResponseFactory $factory)
    {
        $factory->macro('item', function ($request, $data, $type, $status = 200) use ($factory) {

            $response = [
                'data' => [
                    'id' => strval($data['id']),
                    'type' => $type,
                    'attributes' => $data,
                    'links' => [
                        'self' => $request->fullUrl()
                    ],
                ]
            ];

            return $factory->make($response, $status);
        });

        $factory->macro('pagination', function ($request, $paginator, $type, $status = 200) use ($factory) {

            $base_url = $request->url();

            $indices = [
                'current'   => $paginator->currentPage(),
                'first'     => 1,
                'last'      => $paginator->lastPage(),
                'next'      => $paginator->hasMorePages() ? $paginator->currentPage() + 1 : null,
                'prev'      => !$paginator->onFirstPage() ? $paginator->currentPage() - 1 : null,
            ];

            $query_page = !is_null($request->query('page')) ? $request->query('page') : [];

            $query_params = [
                'current'   => !is_null($indices['current']) ? http_build_query(['page' => array_replace_recursive($query_page, ['offset' => $indices['current']])]) : null,
                'first'     => !is_null($indices['first']) ? http_build_query(['page' => array_replace_recursive($query_page, ['offset' => $indices['first']])]) : null,
                'last'      => !is_null($indices['last']) ? http_build_query(['page' => array_replace_recursive($query_page, ['offset' => $indices['last']])]) : null,
                'next'      => !is_null($indices['next']) ? http_build_query(['page' => array_replace_recursive($query_page, ['offset' => $indices['next']])]) : null,
                'prev'      => !is_null($indices['prev']) ? http_build_query(['page' => array_replace_recursive($query_page, ['offset' => $indices['prev']])]) : null,
            ];

            $response = [
                'links' => [
                    'first' => !is_null($indices['first']) ? "{$base_url}?{$query_params['first']}" : null,
                    'last'  => !is_null($indices['last']) ? "{$base_url}?{$query_params['last']}" : null,
                    'next'  => !is_null($indices['next']) ? "{$base_url}?{$query_params['next']}" : null,
                    'prev'  => !is_null($indices['prev']) ? "{$base_url}?{$query_params['prev']}" : null,
                ],
                'meta' => [
                    'pagination' => [
                        'count'         => $paginator->count(),
                        'limit'         => $paginator->perPage(),
                        'offset'        => $paginator->currentPage(),
                        'total_items'   => $paginator->total(),
                        'total_pages'   => $paginator->lastPage(),
                    ]
                ],
                'data' => array_map(function($item) use ($type, $base_url) {
                    return [
                        'id' => strval($item['id']),
                        'type' => $type,
                        'attributes' => $item,
                        'links' => [
                            'self' => "{$base_url}/{$item['id']}",
                        ],
                    ];
                }, $paginator->toArray()['data'])
            ];

            return $factory->make($response, $status);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
