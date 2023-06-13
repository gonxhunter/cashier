<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralException;
use App\Http\Parameters\Criteria;
use App\Http\Parameters\Validation;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\{JsonResponse, Request, Response};
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;

abstract class CRUDController extends Controller
{
    use Validation;
    /**
     * @var BaseService
     */
    protected BaseService $service;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var array
     */
    protected array $customAttributes = [];

    /**
     * CRUDController constructor.
     *
     * @param BaseService $service
     * @param Request $request
     */
    public function __construct(BaseService $service, Request $request)
    {
        $this->service = $service;
        $this->request = $request;
    }

    /**
     * @return JsonResponse
     */
    public function list() : JsonResponse
    {
        return response()->json(
            $this->service->list(Criteria::createFromRequest($this->request))
        );
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(string $id)
    {
        return response()->json($this->service->get($id)->getData());
    }

    /**
     * @return JsonResponse
     *
     * @throws ValidationException|GeneralException
     */
    public function store()
    {
        $data = $this->validate($this->request, $this->getCreateRules(), [], $this->getCustomAttributes());
        return response()->json(
            $this->service->create($data)->getData(),
            Response::HTTP_CREATED
        );
    }

    /**
     * Validation rules of create action
     *
     * @return array
     */
    abstract public function getCreateRules(): array;

    public function getCustomAttributes()
    {
        return $this->customAttributes;
    }

    public function setCustomAttributes(array $customAttributes)
    {
        $this->customAttributes = $customAttributes;
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     *
     * @throws ValidationException
     * @throws ModelNotFoundException
     * @throws GeneralException
     */
    public function update(string $id)
    {
        $data = $this->validate($this->request, $this->getUpdateRules($id), [], $this->getCustomAttributes());
        return response()->json(
            $this->service->update($id, $data)->getData(),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * Validation rules of update action
     *
     * @param string $id
     *
     * @return array
     */
    abstract public function getUpdateRules(string $id): array;

    /**
     * @param string $id
     *
     * @return JsonResponse
     *
     * @throws ModelNotFoundException
     * @throws GeneralException
     */
    public function destroy(string $id)
    {
        $this->service->delete($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
