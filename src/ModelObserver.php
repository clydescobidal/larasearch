<?php

namespace Clydescobidal\Larasearch;

use Illuminate\Database\Eloquent\Model;

class ModelObserver
{
    /**
     * Handle the saved event for the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function saved($model)
    {
        $model->searchable();
    }

    /**
     * Handle the deleted event for the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function deleted($model)
    {
        $model->unsearchable();
    }

    /**
     * Handle the force deleted event for the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function forceDeleted($model)
    {
        $model->unsearchable();
    }

    /**
     * Handle the restored event for the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function restored($model)
    {
        $model->searchable();
    }
}
