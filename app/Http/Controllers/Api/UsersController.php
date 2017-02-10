<?php namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;

class UsersController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return response(['message' => "Hello from users"], 200)
            ->withHeaders([
                'Content-Type' => 'application/vnd.api+json'
            ]);
    }
}