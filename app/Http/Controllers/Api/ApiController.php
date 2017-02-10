<?php namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;

class ApiController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return response(['message' => "Hello from API root"], 200)
            ->withHeaders([
                'Content-Type' => 'application/vnd.api+json'
            ]);
    }
}