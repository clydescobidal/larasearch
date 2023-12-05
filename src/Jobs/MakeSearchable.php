<?php

namespace Clydescobidal\Larasearch\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

        // Make unsearchable before making searchable
        $unsearchable = new MakeUnsearchable($this->model);
        $unsearchable->handle();

        foreach ($this->model->toSearchableArray() as $column => $value) {
            if (trim($value)) {
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

        if ($cache) {
            $cache->flush();
        }
    }
}
