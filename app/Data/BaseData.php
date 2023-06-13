<?php
namespace App\Data;

use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class BaseData extends Data
{
    public function setData($data): static
    {
        return self::from($data);
    }

    public function setCollection($data): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        return self::collection($data);
    }
}

