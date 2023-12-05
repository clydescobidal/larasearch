<?php

namespace Clydescobidal\Larasearch;

use Clydescobidal\Larasearch\Jobs\MakeSearchable;
use Clydescobidal\Larasearch\Jobs\MakeUnsearchable;

class SyncEngine
{
    public static function makeSearchable($model)
    {
        $queue = config('larasearch.queue');
        $searchableJob = new MakeSearchable($model);
        if ($queue) {
            dispatch($searchableJob);
        } else {
            $searchableJob->handle();
        }
    }

    public static function makeUnsearchable($model)
    {
        $queue = config('larasearch.queue');
        $unsearchableJob = new MakeUnsearchable($model);

        if ($queue) {
            dispatch($unsearchableJob);
        } else {
            $unsearchableJob->handle();
        }
    }
}
