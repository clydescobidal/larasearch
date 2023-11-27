<?php

namespace Clydescobidal\Larasearch;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
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
        $cacheKey = $searchableType.$this->searchQuery;
        $cache = config('larasearch.cache') ? Cache::tags($searchableType) : null;

        if ($cache && $cache->has($cacheKey)) {
            $this->cachedData = $cache->get($cacheKey);

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
            $cache->put($cacheKey, $builder->get());
        }

        return $builder;
    }

    public function get($columns = ['*'])
    {
        echo 'fram cache';

        return $this->cachedData;
    }
}
