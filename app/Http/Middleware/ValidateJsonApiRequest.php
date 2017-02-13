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
        $errors = [];
        $error_code = null;

        $regex_json_api_media_type_without_params = '/application\/vnd\.api\+json(\,.*)?$/';

        // Missing request Content-Type header
        if (!$request->hasHeader('Content-Type')) {

            $error_code = 400;
            $errors = JsonApiUtils::makeErrorObjects([[
                'title' => "Missing request Content-Type header",
                'detail' => "Clients MUST send all JSON API data in request documents with the header Content-Type: application/vnd.api+json without any media type parameters."
            ]], $error_code);
        }

        // Invalid request Content-Type header
        else if ($request->header('Content-Type') !== 'application/vnd.api+json') {

            $error_code = 415;
            $errors = JsonApiUtils::makeErrorObjects([[
                'title' => "Invalid request Content-Type header",
                'detail' => "Clients MUST send all JSON API data in request documents with the header Content-Type: application/vnd.api+json without any media type parameters."
            ]], $error_code);
        }

        // Invalid request Accept header
        else if ($request->hasHeader('Accept') && !preg_match($regex_json_api_media_type_without_params, $request->header('Accept'))) {

            $error_code = 406;
            $errors = JsonApiUtils::makeErrorObjects([[
                'title' => "Invalid request Accept header",
                'detail' => "Clients that include the JSON API media type in their Accept header MUST specify the media type there at least once without any media type parameters."
            ]], $error_code);
        }

        // if request method requires request data
        else if (in_array($request->method(), ['POST', 'PATCH'])) {

            $request_data = $request->all();

            // validate request data : data
            if (!array_key_exists('data', $request_data)) {

                $error_code = 422;
                $errors = JsonApiUtils::makeErrorObjects([[
                    'title' => "Invalid request",
                    'detail' => "The request MUST include a single resource object as primary data."
                ]], $error_code);
            }

            // validate request data : data.type
            else if (!array_key_exists('type', $request_data['data'])) {

                $error_code = 422;
                $errors = JsonApiUtils::makeErrorObjects([[
                    'title' => "Invalid request",
                    'detail' => "The request resource object MUST contain at least a type member."
                ]], $error_code);
            }
        }

        // respond with error
        if (!empty($errors)) {
            $content = JsonApiUtils::makeResponseObject([
                'errors' => $errors
            ], $request->fullUrl());

            return response($content, $error_code);
        }

        // ...
        return $next($request);
    }
}
