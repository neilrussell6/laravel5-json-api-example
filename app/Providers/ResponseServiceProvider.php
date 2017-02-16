<?php namespace App\Providers;

use App\Utils\JsonApiResponseMacroUtils;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param ResponseFactory $factory
     */
    public function boot(ResponseFactory $factory)
    {
        $factory->macro('item', function (Request $request, $data, $model, $status = 200) use ($factory) {
            $full_url = $request->fullUrl();
            return $factory->make(JsonApiResponseMacroUtils::makeItemResponse($data, $model, $full_url), $status);
        });

        $factory->macro('pagination', function (Request $request, LengthAwarePaginator $paginator, $model, $status = 200) use ($factory) {
            $base_url = $request->url();
            $query_params = $request->query();
            return $factory->make(JsonApiResponseMacroUtils::makePaginationResponse($paginator, $model, $base_url, $query_params), $status);
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
