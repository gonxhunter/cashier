<?php

namespace App\Services;

use App\Models\Career;

class CareerService extends BaseService
{

    protected static array $relations = ['employees'];

    public function __construct(
        Career $career
    ) {
        parent::__construct($career);
    }
}
