<?php

namespace Clydescobidal\Larasearch\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeSearchable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The models to be made searchable.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $model;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $models
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Handle the job.
     */
    public function handle()
    {
        $searchableType = $this->model::class;
        $searchableId = $this->model->getKey();
        $cache = config('larasearch.cache') ? Cache::tags($searchableType) : null;

        foreach ($this->model->toSearchableArray() as $column => $value) {
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

        if ($cache) {
            $cache->flush();
        }
    }
}
