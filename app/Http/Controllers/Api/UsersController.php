<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UsersController extends Controller
{
    /**
     * UsersController constructor
     *
     * @param User $model
     */
    public function __construct (User $model)
    {
        parent::__construct($model);
    }

    /**
     * users/{id}/relationships/projects
     * users/{id}/projects
     *
     * @param Request $request
     * @param $user
     * @return mixed
     */
    public function projects (Request $request, User $user)
    {
        return Response::related($request, $this->model, $user, 'projects', 200);
    }

    /**
     * users/{id}/relationships/tasks
     * users/{id}/tasks
     *
     * @param Request $request
     * @param $user
     * @return mixed
     */
    public function tasks (Request $request, User $user)
    {
        return Response::related($request, $this->model, $user, 'tasks', 200);
    }
}
