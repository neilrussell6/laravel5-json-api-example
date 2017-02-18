<?php namespace App\Http\Controllers;

require(app_path('Functions/build_http_url.php'));

use App\Utils\JsonApiUtils;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

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
    public function __construct ($model)
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
    public function index (Request $request)
    {
        $page_args = $request->query('page');

        // paginate request
        $pagination_options = $this->makePaginationOptions($page_args);
        $paginator = $this->model->paginate($pagination_options['limit'], ['*'], "page['offset']", $pagination_options['offset']);
        
        // if no pagination arguments are provided,
        // and the result count falls within the PAGINATION_LIMIT
        // then return as normal collection
        if (is_null($page_args) && !$paginator->hasMorePages()) {
            return Response::collection($request, $paginator->getCollection(), $this->model, 200);
        }

        // otherwise return paginated response
        return Response::pagination($request, $paginator, $this->model, 200);
    }

    /**
     * return single resource item
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function show (Request $request, $id)
    {
        $resource = $this->model->findOrFail($id);
        return Response::item($request, $resource, $this->model, 200);
    }

    /**
     * validates input, then creates a new resource item.
     * returns either: validation error, creation error or successfully created new resource item.
     *
     * @param Request $request
     * @return mixed
     */
    public function store (Request $request)
    {
        $request_data = $request->all();

        $request_data_validation = $this->validateRequestData($request_data, $request->fullUrl());

        // respond with errors
        if (!empty($request_data_validation['errors'])) {
            return response([ 'errors' => $request_data_validation['errors'] ], $request_data_validation['error_code']);
        }

        // create & find resource
        $result = $this->model->create($request_data['data']['attributes']);
        $resource = $this->model->findOrFail($result->id);

        // return newly created resource
        return Response::item($request, $resource->toArray(), $this->model, 201);
    }

    /**
     * validates input, then update the given resource item.
     * returns either: validation error, creation error or successfully created new resource item.
     *
     * @param Request $request
     * @return mixed
     */
    public function update (Request $request, $id)
    {
        $request_data = $request->all();
        $request_data_validation = $this->validateRequestData($request_data, $request->fullUrl());

        // respond with error
        if (!empty($request_data_validation['errors'])) {
            return response([ 'errors' => $request_data_validation['errors'] ], $request_data_validation['error_code']);
        }

        // find & update resource
        $resource = $this->model->findOrFail($id);
        $resource->fill($request_data['data']['attributes']);
        $resource->save();

        // return updated resource
        return Response::item($request, $resource->toArray(), $this->model, 200);
    }

    /**
     * deletes the target resource item.
     * returns either: deletion error or no content.
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy (Request $request, $id)
    {
        $this->model->destroy($id);

        // return no content
        return response([], 204);
    }

    // ----------------------------------------------------
    // utils
    // ----------------------------------------------------

    /**
     * validate request data
     * returns and array of JSON API error objects
     *
     * @param $request_data
     * @return array
     */
    protected function validateRequestData ($request_data)
    {
        $result = [
            'errors' => [],
            'error_code' => null
        ];

        // validate request data : data.type
        if ($this->model['type'] !== $request_data['data']['type']) {

            $result['error_code'] = 422;
            $result['errors'] = JsonApiUtils::makeErrorObjects([[
                'title' => "Invalid request",
                'detail' => "The request resource object type member does not match the request endpoint."
            ]], $result['error_code']);
        }

        else {

            // validate attributes
            $attributes = array_key_exists('attributes', $request_data['data']) ? $request_data['data']['attributes'] : [];
            $validator = Validator::make($attributes, $this->model->rules, $this->model->messages);

            if ($validator->fails()) {
                $result['error_code'] = 422;
                $result['errors'] = JsonApiUtils::makeErrorObjectsFromAttributeValidationErrors($validator->errors()->getMessages(), $result['error_code']);
            }
        }

        return $result;
    }

    /**
     * make pagination options
     *
     * @param $page_args
     * @return array
     */
    protected function makePaginationOptions ($page_args)
    {
        $result = [
            'limit' => self::PAGINATION_LIMIT,
            'offset' => 1,
        ];

        if (is_null($page_args)) {
            return $result;
        }

        if (array_key_exists('limit', $page_args) && (int) $page_args['limit'] < self::PAGINATION_LIMIT) {
            $result['limit'] = (int) $page_args['limit'];
        }

        if (array_key_exists('offset', $page_args)) {
            $result['offset'] = (int) $page_args['offset'];
        }

        return $result;
    }
}
