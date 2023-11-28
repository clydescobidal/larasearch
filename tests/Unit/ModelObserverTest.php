<?php

namespace Clydescobidal\Larasearch\Tests\Unit;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Clydescobidal\Larasearch\ModelObserver;

class ModelObserverTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function test_saved_handler_makes_model_searchable()
    {
        $observer = new ModelObserver;
        $model = m::mock();
        $model->shouldReceive('searchable')->once();
        $observer->saved($model);
    }

    public function test_deleted_handler_makes_model_unsearchable()
    {
        $observer = new ModelObserver;
        $model = m::mock();
        $model->shouldReceive('unsearchable')->once();
        $observer->deleted($model);
    }

    public function test_force_deleted_handler_makes_model_unsearchable()
    {
        $observer = new ModelObserver;
        $model = m::mock();
        $model->shouldReceive('unsearchable')->once();
        $observer->forceDeleted($model);
    }

    public function test_restored_handler_makes_model_unsearchable()
    {
        $observer = new ModelObserver;
        $model = m::mock();
        $model->shouldReceive('searchable')->once();
        $observer->restored($model);
    }
}
