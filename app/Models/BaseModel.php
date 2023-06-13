<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Spatie\LaravelData\WithData;

/**
 * Class BaseModel
 * @package App\Models
 */
class BaseModel extends Model
{
    use WithData;

    public const DEFAULT_ORDER_SCOPE = 'defaultOrder';

    public $incrementing = false;

    public $keyType = 'string';

    protected string $orderByColumn = 'created_at';

    protected string $orderByDirection = 'desc';

    /**
     * The "booting" method of the model.
     */
    protected static function booting(): void
    {
        static::creating(
            function (self $model): void {
                // Automatically generate a UUID if using them, and not provided.
                if (empty($model->{$model->getKeyName()})) {
                    $model->setAttribute($model->getKeyName(), $model->generateUuid());
                }
            }
        );

        // uuid models are ordered by created_at
        static::addGlobalScope(
            self::DEFAULT_ORDER_SCOPE,
            function (Builder $builder) {
                $builder->scopes('defaultOrderBy');
            }
        );
    }

    /**
     * casts `array` returns empty [] if the value is null
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function castAttribute($key, $value)
    {
        if ($this->getCastType($key) === 'array' && is_null($value)) {
            return [];
        }

        return parent::castAttribute($key, $value);
    }

    /**
     * Get a new version 4 (random) UUID.
     */
    public function generateUuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * Scope a query to set a default order.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDefaultOrderBy($query): Builder
    {
        return $query->orderBy($this->getTable() . '.' . $this->orderByColumn, $this->orderByDirection);
    }
}
