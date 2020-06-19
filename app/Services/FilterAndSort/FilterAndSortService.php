<?php

namespace App\Services\FilterAndSort;

use ReflectionClass;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class FilterAndSortService
{
    private $tableColumns;
    private $filterAndSortOnColumns;
    private $supportedQueryParameters;

    public function __construct()
    {
        $this->supportedQueryParameters =
            explode(
                ',',
                env('SUPPORTED_QUERY_PARAMETERS', [
                    'sort_by', 'desc', 'per_page', 'unique', 'page' // 'page' is being used inside the paginated links
                ])
            );
    }

    private function getTableColumns(Model $model)
    {
        $tableName = $model->getTable();
        $this->tableColumns = Schema::getColumnListing($tableName);

        /**
         *  ? Retrieving the MODEL name with Full namespace from the Model object (i.e. 'App\User')
         *  ? Creating Reflection class object for the Model
         *  ? Getting the value of constant ENABLE_FILTER_FOR_COLUMNS
         **/
        $className = get_class($model);
        $reflection = new ReflectionClass($className);
        $this->filterAndSortOnColumns = $reflection->getConstant('ENABLE_FILTER_SORT_ON');
    }

    private function filterCollection(Collection $collection)
    {
        /**
         *  ! Ensuring that the ENABLE_FILTER_FOR_COLUMNS is defined
         *      ? IF the 'const ENABLE_FILTER_FOR_COLUMNS' is defined inside the MODEL
         *          * The query parameter must be one of the FILTER_ENABLED_COLUMNS's values  
         *      ? ELSE
         *          * The query parameter must be one of the tables's columns   
         */
        $tableFilterEnabledColumnsCollection = collect();
        if (is_array($this->filterAndSortOnColumns) && sizeOf($this->filterAndSortOnColumns) > 0) {
            $tableFilterEnabledColumnsCollection = collect($this->filterAndSortOnColumns);
        } else {
            $tableFilterEnabledColumnsCollection = collect($this->tableColumns);
        }

        /**
         *  ! Unsetting all the Query Parameters which are not being used to the Filter the collection
         *      ? Because, $collection = $collection->where($query, $value); used in the 'Filter logic' returns nothing when encountering these Query parameters   
         */
        $queryParametersForFiltering = Request::query();
        foreach ($this->supportedQueryParameters as $value) {
            unset($queryParametersForFiltering[$value]);
        }

        /**
         * ! Filter logic
         * TODO: Find a way to return the Validation errors if the Filter parameter does not belongs to the $tableFilterEnabledColumnsCollection created in the previous step
         *        https://laravel-json-api.readthedocs.io/en/latest/fetching/filtering/  
         */
        foreach ($queryParametersForFiltering as $query => $value) {
            /**
             *  ? The Query Parameter must be present and has a value.
             *  *    AND
             *  ? The query parameter must be one of the table field's or is defined inside the 'const ENABLE_FILTER_FOR_COLUMNS' in the corresponding Model.       
             *      * Need Ternary operation to return 'boolean' for Query parameter 'id':
             *          * Since $collection->search() returns 0 for the Query parameter 'id' which is mostly at the 0th index in all the database tables
             *          * And 0 inside the If condition will fail the condition resulting in Non-filtered results  
             */
            if (isset($query, $value) && ($tableFilterEnabledColumnsCollection->search($query) >= 0 ? true : false)) {
                $collection = $collection->where($query, $value);
            }
        }

        return $collection;
    }

    private function validate()
    {
        // ? Performing the Validation here to eliminate the need of creating the Request Validation for each Controller's Index method where SORTING will be used 
        Request::validate([
            'sort_by' => [
                'bail',         // 'bail' makes sure that the Request Stops on the First Validation Failure
                'sometimes',
                'required',
                Rule::in(
                    is_array($this->filterAndSortOnColumns) && sizeOf($this->filterAndSortOnColumns) > 0 
                        ? $this->filterAndSortOnColumns : $this->tableColumns
                ),  // The sort_by Query parameter could only use Columns defined inside the mode OR the table's original columns
            ],
            'desc' => 'bail|sometimes|required|string|in:true,false,1,0',
        ]);
    }

    private function sortCollection(Collection $collection)
    {
        if (Request::has('sort_by')) {
            $sortByQueryParameter = Request::input('sort_by');

            if (Request::has('desc') && Request::input('desc') === "true" || Request::input('desc') === "1") {
                $collection = $collection->sortByDesc->{$sortByQueryParameter}; // sortBy is a higher order Collection message  
            } else {
                $collection = $collection->sortBy->{$sortByQueryParameter};     // sortBy is a higher order Collection message
            }
        }

        return $collection;
    }

    // Receiving the Collection and Model instance
    public function apply(Collection $collection, Model $model)
    {
        $this->getTableColumns($model);

        $filteredCollection = $this->filterCollection($collection);

        $this->validate();
        $filteredAndSortedCollection = $this->sortCollection($filteredCollection);

        return $filteredAndSortedCollection;
    }
}
