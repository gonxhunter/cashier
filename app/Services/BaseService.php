<?php

namespace App\Services;

use App\Exceptions\GeneralException;
use App\Http\Parameters\Criteria;
use App\Models\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Str;

class BaseService
{
    protected BaseModel $model;
    protected static array $relations = [];
    protected array $counts = [];

    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }

    public function getModel(): BaseModel
    {
        return $this->model;
    }

    /**
     * @param Criteria $criteria
     *
     * @return LengthAwarePaginator
     */
    public function list(Criteria $criteria)
    {
        $query = $this->newQuery(false)->scopes($this->loadScopes($criteria->getFilters()));

        return $this->applyOrderBy($query, $criteria->getSorts())
            ->with(static::$relations)
            ->withCount($this->counts)
            ->paginate($criteria->getLimit(), ['*'], config('pagination.page_name'), $criteria->getPage());

    }

    /**
     * @param $query
     * @param $orderBys
     *
     * @return Builder
     */
    protected function applyOrderBy(Builder $query, $orderBys): Builder
    {
        $orderByScopes = [];
        $isOrderedBy = false;
        foreach ($orderBys as $column => $direction) {
            if (!in_array($direction, ['asc', 'desc'], true)) {
                continue;
            }
            $scopeName = 'OrderBy' . Str::camel($column);
            if ($this->model->hasNamedScope($scopeName)) {
                $orderByScopes[$scopeName] = $direction;
            } else {
                $query->orderBy($column, $direction);
            }
            $isOrderedBy = true;
        }
        if ($isOrderedBy) {
            $query->withoutGlobalScope('defaultOrder');
        }

        return $query->scopes($orderByScopes);
    }

    /**
     * @param string $id
     *
     * @return Model
     *
     * @throws ModelNotFoundException
     */
    public function get(string $id)
    {
        return $this->newQuery()->with(static::$relations)->findOrFail($id);
    }

    public function getEnumerable(array $ids, $relations = [], $counts = []): Enumerable
    {
        $query = $this->model->whereIn('id', $ids);
        if ($relations) {
            $query->with($relations);
        }
        if ($counts) {
            $query->withCount($counts);
        }

        return $query->cursor();
    }

    /**
     * @param array $data
     *
     * @return Model
     *
     * @throws GeneralException
     */
    public function create(array $data): Model
    {
        return tap(
            $this->model->create($data),
            function ($instance) {
                if (!$instance) {
                    throw new GeneralException(__('exceptions.actions.create_failed'));
                }
            }
        )->fresh(static::$relations);
    }

    /**
     * @param $id
     * @param array $data
     *
     * @return Model
     *
     * @throws ModelNotFoundException|GeneralException
     */
    public function update($id, array $data): Model
    {
        $query = $this->newQuery();
        $model = tap(
            $query->findOrFail($id),
            function ($instance) use ($data) {
                if (!$instance->update($data)) {
                    throw new GeneralException(__('exceptions.actions.update_failed'));
                }
            }
        );
        return $model->load(static::$relations);
    }

    /**
     * @param $id
     *
     * @throws ModelNotFoundException|GeneralException
     */
    public function delete($id): void
    {
        $query = $this->newQuery();
        $model = $query->findOrFail($id);
        try {
            $model->delete();
        } catch (\Throwable $e) {
            throw new GeneralException(__('exceptions.actions.delete_failed'));
        }
    }

    /**
     * @param bool $withoutGlobalOrder
     * @param bool $withStateFilter
     * @param bool $withAccountHolderFilter
     * @return Builder|Model|mixed
     */
    protected function newQuery($withoutGlobalOrder = true, $withStateFilter = true, $withAccountHolderFilter = true)
    {
        $query = $this->model;
        if ($withoutGlobalOrder) {
            $query->withoutGlobalScopes([$this->model::DEFAULT_ORDER_SCOPE]);
        }

//        if ($withStateFilter && $this->stateCode) {
//            $query = $query->scopes($this->loadScopes(['stateCode' => $this->stateCode]));
//        }
//        if ($withAccountHolderFilter) {
//            $query = $query->scopes($this->loadScopes(['accountHolderId' => $this->accountHolderId]));
//        }

        return $query;
    }

    /**
     * @param $scopes
     * @return array
     */
    protected function loadScopes($scopes): array
    {
        $namedScoped = [];
        foreach ($scopes as $name => $args) {
            $scopeName = Str::camel($name);
            if (!$this->model->hasNamedScope($scopeName) || is_null($args)) {
                continue;
            }
            if (is_string($args) && Str::contains($args, ',')) {
                $args = explode(',', $args);
            }

            $namedScoped[$scopeName] = $args;
        }

        return $namedScoped;
    }
}
