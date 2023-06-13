<?php
namespace App\Data;

use Spatie\LaravelData\Data;

class EmployeeData extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $first_name,
        public ?string $last_name,
        public ?string $career_id,
        public ?string $bod,
        public ?string $feature_image,
        public ?CareerData $career
    ) {
    }
}

