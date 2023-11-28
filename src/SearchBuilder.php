<?php

namespace Clydescobidal\Larasearch;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class SearchBuilder extends EloquentBuilder
{
    public $searchQuery;

    public $cachedData = [];

    public function __construct(Model $model, string $searchQuery)
    {
        $this->query = DB::table($model->getTable());
        $this->model = $model;
        $this->searchQuery = $searchQuery;
    }

    /**
     * Perform a search against the model's indexed data.
     *
     * @param  string  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search() : \Illuminate\Database\Eloquent\Builder
    {
        $searchableType = $this->model::class;
        $searchableKeyName = $this->model->getKeyName();
        $cache = config('larasearch.cache') ? Cache::tags($searchableType) : null;

        if ($cache) {
            $this->cachedData = $cache->get($this->searchQuery, Collection::make([]));

            return $this;
        }

        $modelIds = DB::table(config('larasearch.table'))
            ->select('searchable_id')
            ->where('searchable_type', $searchableType)
            ->where('value', 'LIKE', "%{$this->searchQuery}%")
            ->distinct()
            ->get();
        $modelIds = $modelIds->pluck('searchable_id')->toArray();
        $builder = $searchableType::whereIn($searchableKeyName, $modelIds);

        if ($cache) {
            $cache->put($this->searchQuery, $builder->get());
        }

        return $builder;
    }

    public function get($columns = ['*'])
    {
        return $this->cachedData;
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
}
