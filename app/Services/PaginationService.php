<?php

namespace App\Services;

use Illuminate\Support\Facades\Request;
// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationService
{
    private $perPage;
    private $page;

    public function __construct()
    {
        $this->perPage = 20;                                        // Each page will have 20 results only BY DEFAULT
        $this->page = LengthAwarePaginator::resolveCurrentPage();   // To find which page we are on
    }

    public function paginate(Collection $collection)
    {
        // ? Performing the Validation here to eliminate the need of creating the Request Validation for each Controller's Index method where PAGINATION will be used 
        Request::validate([
            'per_page' => 'bail|sometimes|required|integer|min:2|max:50',  // 'bail' makes sure that the Request Stops on the First Validation Failure
        ]);

        if (Request::has('per_page')) {
            $this->perPage = (int) Request::input('per_page');
        }

        $results = $collection->slice(($this->page - 1) * $this->perPage, $this->perPage)->values();    // Because the first index of collection is 0, but LengthAwarePaginator will start from Page 1

        // ! If we use $paginated, then the other QUERY parameters coming with the GET request such as 'sort_by' will be dropped by default
        $paginated = new LengthAwarePaginator($results, $collection->count(), $this->perPage, $this->page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),   // resolveCurrentPath() will generate the path for another page in the META shown in the GET request
        ]);

        $paginated->appends(Request::all());    // ! So, Appending the other request parameters such as 'sort_by' again
        return $paginated;
    }
}
