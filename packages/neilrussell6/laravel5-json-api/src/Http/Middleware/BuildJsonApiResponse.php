<?php namespace NeilRussell6\Laravel5JsonApi\Http\Middleware;

use Closure;
use NeilRussell6\Laravel5JsonApi\Utils\JsonApiUtils;

class BuildJsonApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle ($request, Closure $next)
    {
        $response = $next($request);

        // respond with already rendered exception page
        // if an exception was thrown
        $exception = $response->exception;
        if ($exception) {
            return $response;
        }

        $original_content = $response->getOriginalContent();

        if (!is_null($original_content)) {

            // make response content and update response
            $content = JsonApiUtils::makeResponseObject($original_content);
            $response->setContent($content);
        }

        $response->header('Content-Type', 'application/vnd.api+json');

        return $response;
    }
}
