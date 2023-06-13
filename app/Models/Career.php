<?php

namespace App\Models;

use App\Data\CareerData;
use App\Data\EmployeeData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Career extends BaseModel
{
    protected $dataClass = CareerData::class;
    protected $fillable = [
        'id',
        'title',
        'description',
        'slug',
        'salary',
        'benefits',
        'city',
        'feature_image',
        'due_date'
    ];

    protected $casts = [
        'employees' => EmployeeData::class,
    ];

    public function scopeCareerId(Builder $query, $value): Builder
    {
        return $query->where('id', $value);
    }

    public function scopeCity(Builder $query, $value): Builder
    {
        return $query->where('city', $value);
    }

    public function employees() : HasMany
    {
        return $this->hasMany(Employee::class, 'career_id', 'id');
    }
}
