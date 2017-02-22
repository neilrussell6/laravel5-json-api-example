<?php namespace App\Providers;

use App\Utils\JsonApiResponseMacroUtils;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;

class ResponseServiceProvider extends ServiceProvider
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

        $factory->macro('related', function (Request $request, $model, $entity, $name, $status = 200) use ($factory) {

            var_dump("XXXX");die();

//            // is minimal ? (return resource identifier object ie. type and id only)
//            $action = $request->route()->getAction();
//            $is_minimal = array_key_exists('is_minimal', $action) && $action['is_minimal'];
//
//            // check how many queries are being created here
//            $relationship = $entity->$name();
//
//            switch (get_class($relationship)) {
//
//                // multiple entity relationships
//
//                default:
//                case HasMany::class:
//                case BelongsToMany::class:
//                case HasManyThrough::class:
//                case HasOneOrMany::class: // TODO: how to handle these?
//
//                    $related_collection = new Collection($relationship->get());
//                    $related_model = !is_null($entity->$name) ? $entity->$name()->getRelated() : null;
//                    return $factory->make(JsonApiResponseMacroUtils::makeCollectionResponse($related_collection, $related_model, $request->url(), $is_minimal), $status);
//
//                // single entity relationships
//
//                case BelongsTo::class:
//                case HasOne::class:
//
//                    $related_data = !is_null($entity->$name) ? $entity->$name->toArray() : null;
//                    $related_model = !is_null($entity->$name) ? $entity->$name()->getRelated() : null;
//                    $include_resource_object_links = true;
//                    return $factory->make(JsonApiResponseMacroUtils::makeItemResponse($related_data, $related_model, $request->url(), $include_resource_object_links, $is_minimal), $status);
//            }
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register ()
    {
        //
    }
}
