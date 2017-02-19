<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProjectsController extends Controller
{
    public $rules = [
        'name' => 'required',
        'status' => 'required'
    ];

    /**
     * ProjectsController constructor
     *
     * @param Project $model
     */
    public function __construct (Project $model)
    {
        parent::__construct($model);
    }

    /**
     * projects/{id}/relationships/tasks
     * projects/{id}/tasks
     *
     * @param Request $request
     * @param $project
     * @return mixed
     */
    public function tasks (Request $request, Project $project)
    {
        return Response::related($request, $this->model, $project, 'tasks', 200);
    }

    /**
     * projects/{id}/relationships/owner
     * projects/{id}/owner
     *
     * @param Request $request
     * @param $project
     * @return mixed
     */
    public function owner (Request $request, Project $project)
    {
        return Response::related($request, $this->model, $project, 'owner', 200);
    }

    /**
     * tasks/{id}/relationships/editors
     * tasks/{id}/editors
     *
     * @param Request $request
     * @param $project
     * @return mixed
     */
    public function editors (Request $request, Project $project)
    {
        return Response::related($request, $this->model, $project, 'editors', 200);
    }
}
