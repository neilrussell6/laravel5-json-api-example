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

//    /**
//     * projects/{id}/tasks
//     *
//     * @param Request $request
//     * @param $id
//     * @param Task $task
//     * @return \Dingo\Api\Http\Response
//     */
//    public function tasks (Request $request, $id, Task $task)
//    {
//        $data = $this->model->findOrFail($id)->tasks;
//        return $this->relationshipResponse($id, $data, Project::class, Task::class, new TaskTransformer());
//    }
//
//    /**
//     * projects/{id}/relationships/tasks
//     *
//     * @param Request $request
//     * @param $id
//     * @param Task $task
//     * @return \Dingo\Api\Http\Response
//     */
//    public function taskRelationships (Request $request, $id, Task $task)
//    {
//        $data = $this->model->findOrFail($id)->tasks(['tasks.id'])->get();
//        return $this->relationshipResponse($id, $data, Project::class, Task::class, new TaskTransformer(), true);
//    }
}
