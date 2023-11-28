<?php

namespace Clydescobidal\Larasearch;

trait Searchable
{
    /**
     * Boot the trait.
     */
    public static function bootSearchable()
    {
        static::observe(new ModelObserver);
    }

    /**
     * Perform a search against the model's indexed data.
     *
     * @param  string  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function search(string $query) : \Illuminate\Database\Eloquent\Builder
    {
        $model = new static;

        return (new SearchBuilder($model, $query))->search();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->toArray();
    }

    /**
     * Make the given model instance searchable.
     */
    public function searchable()
    {
        SyncEngine::makeSearchable($this);
    }

    /**
     * Remove the given model instance from the search index.
     */
    public function unsearchable()
    {
        SyncEngine::makeUnsearchable($this);
    }
}
