<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TasksController extends Controller
{
    public $rules = [
        'name' => 'required',
        'status' => 'required',
        'type' => 'required',
        'priority' => 'required'
    ];

    /**
     * TasksController constructor
     *
     * @param Task $model
     */
    public function __construct (Task $model)
    {
        parent::__construct($model);
    }

    /**
     * $tasks/{id}/relationships/project
     * $tasks/{id}/project
     *
     * @param Request $request
     * @param $task
     * @return mixed
     */
    public function project (Request $request, Task $task)
    {
        return Response::related($request, $this->model, $task, 'project', 200);
    }

}

