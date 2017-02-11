<?php namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApiController extends Controller
{
    /**
     * @return mixed
     */
    public function index(Request $request)
    {
//        var_dump($request->header('Accept'));die();

        // missing Request Content-Type header
        if (!$request->hasHeader('Content-Type')) {

            return response([], 400)
                ->withHeaders([
                    'Content-Type' => 'application/vnd.api+json'
                ]);
        }

        // invalid Request Content-Type header
        if ($request->header('Content-Type') !== 'application/vnd.api+json') {

            return response([], 415)
                ->withHeaders([
                    'Content-Type' => 'application/vnd.api+json'
                ]);
        }

        // invalid Request Accept header
        $regex_json_api_media_type_without_params = '/application\/vnd\.api\+json(\,.*)?$/';

        if ($request->hasHeader('Accept') && !preg_match($regex_json_api_media_type_without_params, $request->header('Accept'))) {

            return response([], 406)
                ->withHeaders([
                    'Content-Type' => 'application/vnd.api+json'
                ]);
        }

        return response([], 200)
            ->withHeaders([
                'Content-Type' => 'application/vnd.api+json'
            ]);
    }
}