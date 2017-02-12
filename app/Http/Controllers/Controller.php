<?php namespace App\Http\Controllers;

use App\Utils\JsonApiUtils;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const PAGINATION_LIMIT = 100;

    protected $model;

    public $messages = [
        'required' => 'The :attribute field is required.',
        'email' => 'A valid email is required for :attribute field.',
        'unique' => ':attribute field must be unique.',
        'min' => ':attribute field must be at least :min character in length.',
    ];

    /**
     * Controller constructor.
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = new $model();
    }

    // ----------------------------------------------------
    // CRUD methods
    // ----------------------------------------------------

    /**
     * return a paginated collection of resource items
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if (!$request->get('is_valid')) {
            return response(null, $request->get('status'));
        }

        $pagination_options = $this->makePaginationOptions($request);
        $paginator = $this->model->paginate($pagination_options['limit'], ['*'], "page['offset']", $pagination_options['offset']);

        return Response::pagination($request, $paginator, $this->model->type, 200);
    }

    /**
     * return single resource item
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        $data = $this->model->findOrFail($id);
        return Response::item($request, $data, $this->model->type, 200);
    }

    // ----------------------------------------------------
    // utils
    // ----------------------------------------------------

    /**
     * make pagination options
     *
     * @param Request $request
     * @return array
     */
    protected function makePaginationOptions(Request $request)
    {
        $page = $request->query('page');

        $result = [
            'limit' => self::PAGINATION_LIMIT,
            'offset' => 1,
        ];

        if (is_null($page)) {
            return $result;
        }

        if (array_key_exists('limit', $page) && (int) $page['limit'] < self::PAGINATION_LIMIT) {
            $result['limit'] = (int) $page['limit'];
        }

        if (array_key_exists('offset', $page)) {
            $result['offset'] = (int) $page['offset'];
        }

        return $result;
    }
}
