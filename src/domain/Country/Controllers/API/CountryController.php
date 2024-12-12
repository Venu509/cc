<?php

namespace Domain\Country\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\Country\Actions\ListCountriesAction;
use Domain\Global\Helpers\EmployeeHelper;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    public function index():JsonResponse
    {
        $params = [
            'search' => request()->has('search') ? request()->get('search') : null,
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'page' => request()->has('page') ? request()->get('page') : 1,
        ];

        return response()->json([
            'status' => true,
            'countries' => (new ListCountriesAction())->execute($params)
        ]);
    }
}
