<?php

namespace Clydescobidal\Larasearch;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class SyncEngine
{
    public static function makeSearchable(Model $model)
    {
        $searchableType = $model::class;
        $searchableId = $model->getKey();

        // invalidate cache
        $cache = config('larasearch.cache');

        if ($cache) {
            Cache::tags($searchableType)->flush();
            // php artisan cache:clear --tags=$searchableType
        }

        foreach ($model->toSearchableArray() as $column => $value) {
            DB::table(config('larasearch.table'))->updateOrInsert(
                [
                    'searchable_type' => $searchableType,
                    'searchable_id' => $searchableId,
                    'column' => $column,
                ],
                [
                    'value' => $value,
                ]
            );
        }
    }
}
