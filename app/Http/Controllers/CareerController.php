<?php

namespace App\Http\Controllers;

use App\Services\CareerService;
use Illuminate\Http\Request;

class CareerController extends CRUDController
{
    /**
     * CareerController constructor
     *
     * @param Request $request
     * @param CareerService $careerService
     */
    public function __construct(
        CareerService $careerService,
        Request $request
    ) {
        parent::__construct($careerService, $request);
    }

    /**
     * Validation rules of create action
     *
     * @return array
     */
    public function getCreateRules() : array
    {
        return [
            'title' =>['required', 'string'],
            'slug' => 'nullable',
            'description' => ['nullable', 'string', 'max:2000'],
            'salary' => 'nullable',
            'benefits' => 'nullable',
            'city' => 'required',
            'feature_image' => 'nullable',
            'due_date' => 'nullable'
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
            'title' =>['required', 'string'],
            'slug' => 'nullable',
            'description' => ['nullable', 'string', 'max:2000'],
            'salary' => 'nullable',
            'benefits' => 'nullable',
            'city' => 'nullable',
            'feature_image' => 'nullable',
            'due_date' => 'nullable'
        ];
    }
}
