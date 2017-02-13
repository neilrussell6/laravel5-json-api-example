<?php namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;

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
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

//    /**
//     * projects/{id}/tasks
//     *
//     * @param Request $request
//     * @param $id
//     * @param Task $task
//     * @return \Dingo\Api\Http\Response
//     */
//    public function tasks(Request $request, $id, Task $task)
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
//    public function taskRelationships(Request $request, $id, Task $task)
//    {
//        $data = $this->model->findOrFail($id)->tasks(['tasks.id'])->get();
//        return $this->relationshipResponse($id, $data, Project::class, Task::class, new TaskTransformer(), true);
//    }
}
