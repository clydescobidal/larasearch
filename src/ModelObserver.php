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
    public function saved(Model $model)
    {
        $model->searchable();
    }

    /**
     * Handle the deleted event for the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function deleted(Model $model)
    {
    }

    /**
     * Handle the force deleted event for the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function forceDeleted(Model $model)
    {
        $model->unsearchable();
    }

    /**
     * Handle the restored event for the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function restored(Model $model)
    {
    }
}
