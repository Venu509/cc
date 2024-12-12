<?php

namespace Domain\Country\Controllers;

use App\Http\Controllers\Controller;
use Domain\Global\Helpers\EmployeeHelper;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    public function fetch():JsonResponse
    {
        $params = [
            'search' => request()->has('search') ? request()->get('search') : null,
        ];

        return response()->json([
            'status' => true,
            'countries' => (new EmployeeHelper())->countries($params)
        ]);
    }
}
