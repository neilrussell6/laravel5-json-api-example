<?php namespace NeilRussell6\Laravel5JsonApi\Providers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use NeilRussell6\Laravel5JsonApi\Utils\JsonApiResponseMacroUtils;

class Laravel5JsonApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param ResponseFactory $factory
     */
    public function boot (ResponseFactory $factory)
    {
        $factory->macro('collection', function (Request $request, Collection $collection, $model, $status = 200, $include_resource_object_links = true, $is_minimal = false) use ($factory) {
            return $factory->make(JsonApiResponseMacroUtils::makeCollectionResponse($collection, $model, $request->url(), $include_resource_object_links, $is_minimal), $status);
        });

        $factory->macro('item', function (Request $request, $data, $model, $status = 200, $include_resource_object_links = false, $is_minimal = false) use ($factory) {
            return $factory->make(JsonApiResponseMacroUtils::makeItemResponse($data, $model, $request->url(), $include_resource_object_links, $is_minimal), $status);
        });

        $factory->macro('pagination', function (Request $request, LengthAwarePaginator $paginator, $model, $status = 200) use ($factory) {
            return $factory->make(JsonApiResponseMacroUtils::makePaginationResponse($paginator, $model, $request->fullUrl(), $request->url(), $request->query()), $status);
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
