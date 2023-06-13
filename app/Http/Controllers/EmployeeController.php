<?php

namespace App\Http\Controllers;

use App\Data\EmployeeData;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends CRUDController
{
    /**
     * EmployeeController constructor
     *
     * @param Request $request
     * @param EmployeeService $employeeService
     * @param EmployeeData $data
     */
    public function __construct(
        EmployeeService $employeeService,
        Request $request,
        EmployeeData $data
    ) {
        parent::__construct($employeeService, $request, $data);
    }

    /**
     * Validation rules of create action
     *
     * @return array
     */
    public function getCreateRules() : array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'bod' => ['nullable', 'string', 'max:2000'],
            'career_id' => 'required',
        ];
    }

    /**
     * Validation rules of edit action
     *
     * @return array
     */
    public function getUpdateRules(string $id): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'bod' => ['nullable', 'string', 'max:2000'],
            'career_id' => 'required',
        ];
    }
}
