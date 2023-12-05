<?php

namespace Clydescobidal\Larasearch\Console;

use Clydescobidal\Larasearch\Searchable;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class MakeModelsSearchable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:searchable {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make models searchable';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $modelArg = $this->argument('model');
        $model = app()->make($modelArg);
        if (! $model instanceof Model) {
            $this->error("{$modelArg} is not an instance of ".Model::class);

            return 1;
        }
        if (! in_array(Searchable::class, class_uses_recursive($model::class))) {
            $this->error("{$modelArg} does not have the ".Searchable::class.' trait');
        }

        $model::chunk(100, function ($searchables) :void {
            foreach ($searchables as $searchable) {
                $searchable->searchable();
            }
        });

        return 0;
    }
}
