<?php

namespace App\Services\FilterAndSort;

use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class FilterAndSortService
{
    private $tableColumns;
    private $tableColumnsCollection;

    public function __construct()
    {
        //
    }

    private function getTableColumns(Model $model)
    {
        $tableName = $model->getTable();
        $this->tableColumns = Schema::getColumnListing($tableName);
        $this->tableColumnsCollection = collect($this->tableColumns);
    }

    private function filterCollection(Collection $collection)
    {
        foreach (Request::query() as $query => $value) {
            /**
             * ? The Query Parameter must be present and has a value.
             * *    AND
             * ? The query parameter must be one of the database tables's field.
             */
            if (isset($query, $value) && $this->tableColumnsCollection->search($query)) {
                $collection = $collection->where($query, $value);
            }
        }

        return $collection;
    }

    private function sortCollection(Collection $collection)
    {
        // ? Performing the Validation here to eliminate the need of creating the Request Validation for each Controller's Index method where SORTING will be used 
        Request::validate([
            'sort_by' => [
                'bail',                             // 'bail' makes sure that the Request Stops on the First Validation Failure
                'sometimes',
                'required',
                Rule::in($this->tableColumns),      // The sort_by Query paramere could only be sorted by the table columns
            ],
        ]);

        if (Request::has('sort_by')) {
            $sortByQueryParameter = Request::input('sort_by');
            $collection = $collection->sortBy->{$sortByQueryParameter}; // sortBy is a higher order Collection message          
        }

        return $collection;
    }

    // Receiving the Collection and Model instance
    public function apply(Collection $collection, Model $model)
    {
        $this->getTableColumns($model);

        $filteredCollection = $this->filterCollection($collection);
        $filteredAndSortedCollection = $this->sortCollection($filteredCollection);

        return $filteredAndSortedCollection;
    }
}
