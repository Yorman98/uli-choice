<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Remove unnecessary pagination data
     *
     * @param array $products
     */
    protected function removeUnnecessaryPaginationData(array &$products)
    {
        unset($products['first_page_url']);
        unset($products['last_page_url']);
        unset($products['next_page_url']);
        unset($products['prev_page_url']);
        unset($products['path']);
        unset($products['from']);
        unset($products['to']);
        unset($products['links']);
    }
}
