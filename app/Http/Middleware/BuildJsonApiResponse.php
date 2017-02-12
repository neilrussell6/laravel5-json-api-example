<?php namespace App\Http\Middleware;

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

        // build response content

        $original_content = $response->getOriginalContent();
        $default_content = [
            'jsonapi' => [
                'version' => '1.0'
            ],
            'links' => [
                'self' => $request->fullUrl()
            ]
        ];

        // set response content & headers

        $response->setContent(array_merge_recursive($default_content, $original_content));
        $response->header('Content-Type', 'application/vnd.api+json');

        return $response;
    }
}
