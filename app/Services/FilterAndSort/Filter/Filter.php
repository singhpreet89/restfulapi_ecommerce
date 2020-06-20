<?php

namespace App\Services\FilterAndSort\Filter;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class Filter
{
    private $filterAndSortOnColumns;
    private $tableColumns;
    private $excludeQueryParamsFromFilter;

    public function __construct($filterAndSortOnColumns, $tableColumns)
    {
        $this->filterAndSortOnColumns = $filterAndSortOnColumns;
        $this->tableColumns = $tableColumns;
        $this->excludeQueryParamsFromFilter =
            explode(
                ',',
                env('SUPPORTED_QUERY_PARAMETERS', [
                    'sort_by',  // 'sort_by' is used only for Sorting 
                    'desc',     // 'desc' is used for Sorting in Reverse order
                    'per_page', // 'per_page' is used in Pagination
                    'unique',   // 'unique' is used in the Relevant controllers to fetch the unique results
                    'page'      // 'page' is being used inside the Paginated links
                ])
            )
        ;
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
        foreach ($this->excludeQueryParamsFromFilter as $value) {
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

    public function apply(Collection $collection)
    {
        return $this->filterCollection($collection);
    }
}
