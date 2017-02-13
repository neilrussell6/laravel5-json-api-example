<?php namespace App\Http\Middleware;

use App\Utils\JsonApiUtils;
use Closure;

class BuildJsonApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // respond with already rendered exception page
        // if an exception was thrown
        $exception = $response->exception;
        if ($exception) {
            return $response;
        }

        // make response content and update response
        $content = JsonApiUtils::makeResponseObject($response->getOriginalContent(), $request->fullUrl());
        $response->setContent($content);
        $response->header('Content-Type', 'application/vnd.api+json');

        return $response;
    }
}
