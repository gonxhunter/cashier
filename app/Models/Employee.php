<?php

namespace App\Models;

use App\Data\CareerData;
use App\Data\EmployeeData;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends BaseModel
{
    protected $dataClass = EmployeeData::class;
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'career_id',
        'bod',
        'feature_image',
    ];

    protected $casts = [
        'career' => CareerData::class,
    ];

    protected $relations = ['career'];

    public function career() : BelongsTo
    {
        return $this->belongsTo(Career::class, 'career_id', 'id');
    }
}
