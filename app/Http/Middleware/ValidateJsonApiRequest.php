<?php namespace App\Http\Middleware;

use App\Utils\JsonApiUtils;
use Closure;

class ValidateJsonApiRequest
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
        $error = [];

        // Missing request Content-Type header
        if (!$request->hasHeader('Content-Type')) {

            $request->attributes->add(['status' => 400]);
            $error['messages'] = JsonApiUtils::makeErrorObject([[
                'title' => "Missing request Content-Type header",
                'detail' => "Clients MUST send all JSON API data in request documents with the header Content-Type: application/vnd.api+json without any media type parameters."
            ]], 400);
        }

        // Invalid request Content-Type header
        else if ($request->header('Content-Type') !== 'application/vnd.api+json') {

            $request->attributes->add(['status' => 415]);
            $error['messages'] = JsonApiUtils::makeErrorObject([[
                'title' => "Invalid request Content-Type header",
                'detail' => "Clients MUST send all JSON API data in request documents with the header Content-Type: application/vnd.api+json without any media type parameters."
            ]], 415);
        }

        else {

            // Invalid request Accept header
            $regex_json_api_media_type_without_params = '/application\/vnd\.api\+json(\,.*)?$/';

            if ($request->hasHeader('Accept') && !preg_match($regex_json_api_media_type_without_params, $request->header('Accept'))) {

                $request->attributes->add(['status' => 406]);
                $error['messages'] = JsonApiUtils::makeErrorObject([[
                    'title' => "Invalid request Accept header",
                    'detail' => "Clients that include the JSON API media type in their Accept header MUST specify the media type there at least once without any media type parameters."
                ]], 406);
            }
        }

        // set is_valid request attribute so that controller logic can be skipped
        $request->attributes->add(['is_valid' => empty($error)]);

        // ...
        $response = $next($request);

        // respond with already rendered exception page
        // if an exception was thrown
        $exception = $response->exception;
        if ($exception) {
            return $response;
        }

        // update response
        if (!empty($error)) {
            $response->setContent(['errors' => $error['messages']]);
        }

        return $response;
    }
}
