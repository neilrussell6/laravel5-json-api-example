<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * UsersController constructor
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

//    /**
//     * users/{id}/projects
//     *
//     * @param Request $request
//     * @param $id
//     * @param Project $project
//     * @return mixed
//     */
//    public function projects(Request $request, $id, Project $project)
//    {
//        $data = $this->model->findOrFail($id)->projects;
//        return $this->relationshipResponse($id, $data, User::class, Project::class, new ProjectTransformer());
//    }
//
//    /**
//     * users/{id}/relationships/projects
//     *
//     * @param Request $request
//     * @param $id
//     * @param Project $project
//     * @return mixed
//     */
//    public function projectRelationships(Request $request, $id, Project $project)
//    {
//        $data = $this->model->findOrFail($id)->projects(['projects.id'])->get();
//        return $this->relationshipResponse($id, $data, User::class, Project::class, new ProjectTransformer(), true);
//    }
//
//    /**
//     * users/{id}/tasks
//     *
//     * @param Request $request
//     * @param $id
//     * @param Task $task
//     * @return mixed
//     */
//    public function tasks(Request $request, $id, Task $task)
//    {
//        $data = $this->model->findOrFail($id)->tasks;
//        return $this->relationshipResponse($id, $data, User::class, Task::class, new TaskTransformer());
//    }
//
//    /**
//     * users/{id}/relationships/tasks
//     *
//     * @param Request $request
//     * @param $id
//     * @param Task $task
//     * @return mixed
//     */
//    public function taskRelationships(Request $request, $id, Task $task)
//    {
//        $data = $this->model->findOrFail($id)->tasks(['tasks.id'])->get();
//        return $this->relationshipResponse($id, $data, User::class, Task::class, new TaskTransformer(), true);
//    }
}
