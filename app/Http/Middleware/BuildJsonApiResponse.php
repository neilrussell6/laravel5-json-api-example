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
        $original_response = $response->getOriginalContent();

//        $url = $request->fullUrl(); // with args
        $url = $request->url(); // without args

        $response->setContent([
            'jsonapi' => [
                'version' => '1.0'
            ],
            'links' => [
                'self' => $url
            ],
            'data' => $original_response
        ]);

        return $response;
    }
}
