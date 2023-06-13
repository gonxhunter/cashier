<?php

namespace App\Services;

use App\Models\Employee;

class EmployeeService extends BaseService
{
    protected static array $relations = ['career'];
    public function __construct(
        Employee $employee
    ) {
        parent::__construct($employee);
    }
}
