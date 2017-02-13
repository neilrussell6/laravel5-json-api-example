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

    /**
     * validates input, then creates a new resource item.
     * returns either: validation error, creation error or successfully created new resource item.
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $request_data = $request->all();
        $errors = [];
        $error_code = null;

        // validate request data : data.type
        if ($this->model['type'] !== $request_data['data']['type']) {

            $error_code = 422;
            $errors = JsonApiUtils::makeErrorObjects([[
                'title' => "Invalid request",
                'detail' => "The request resource object type member does not match the request endpoint."
            ]], $error_code);
        }

        //        // validate attributes
        //
        //        $attributes = array_key_exists('attributes', $request_data['data']) ? $request_data['data']['attributes'] : [];
        //        $validator = Validator::make($attributes, $this->rules, $this->messages);
        //
        //        if ($validator->fails()) {
        //            $response = TransformerUtils::transformAttributeValidationErrors($validator->errors()->getMessages());
        //            return $this->response->array($response)->statusCode(422);
        //        }

        // respond with error
        if (!empty($errors)) {
            $content = JsonApiUtils::makeResponseObject([
                'errors' => $errors
            ], $request->fullUrl());

            return response($content, $error_code);
        }

        // TODO: store entity
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
