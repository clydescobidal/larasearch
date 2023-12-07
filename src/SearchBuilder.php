<?php

namespace Clydescobidal\Larasearch;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SearchBuilder extends EloquentBuilder
{
    public $searchQuery;

    public $cachedData;

    public $cache;

    public Builder $builder;

    public int $limit = 0;

    public function __construct(Model $model, string $searchQuery)
    {
        $this->query = DB::table($model->getTable());
        $this->model = $model;
        $this->searchQuery = $searchQuery;
        $this->cache = config('larasearch.cache') ? Cache::tags($this->model::class) : null;
        $this->builder = DB::table(config('larasearch.table'));
    }

    public function search() : EloquentBuilder
    {
        $this->builder = $this->builder->select(['searchable_id', 'column', 'value'])
            ->where('searchable_type', $this->model::class);

        if (strlen($this->searchQuery) >= 3) {
            $this->builder = $this->builder->whereRaw("MATCH(value) AGAINST ('*{$this->searchQuery}*' IN BOOLEAN MODE)");
        } else {
            $this->builder = $this->builder->where('value', 'LIKE', "%{$this->searchQuery}%");
        }

        $this->builder = $this->builder->distinct();

        return $this;
    }

    public function get($columns = ['*'])
    {
        $results = Collection::make();
        $cacheKey = "{$this->searchQuery}:{$this->limit}";

        if ($this->cache && $this->cache->has($cacheKey)) {
            $results = $this->cache->get($cacheKey);
        } else {
            $results = $this->builder->get();
            if ($this->cache) {
                $this->cache->put($cacheKey, $results);
            }
        }

        $results = $results instanceof Collection ? $results : Collection::make($results);
        $searchableKeyName = $this->model->getKeyName();

        return $results->map(function ($res) use ($searchableKeyName) {
            return [
                $searchableKeyName => $res->searchable_id,
                $res->column => $res->value,
            ];
        });
    }

    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);
        $perPage = $perPage ?: $this->model->getPerPage();
        $perPage = 1;
        $items = $this->get();
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;
        $this->builder->limit($this->limit);

        return $this;
    }

    public function take(int $limit)
    {
        $this->limit = $limit;
        $this->builder->limit($this->limit);

        return $this;
    }
}
