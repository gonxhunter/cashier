<?php
namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class CareerData extends Data
{
    public function __construct(
        public ?string $id,
        public string $title,
        public ?string $slug,
        public ?string $description,
        public ?string $salary,
        public ?string $benefits,
        public ?string $city,
        public ?string $featureImage,
        public ?string $due_date,
        /** @var EmployeeData[] */
        public ?DataCollection $employees,
    ) {
    }
}

