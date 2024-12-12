<?php

namespace Domain\Filter\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\Global\Helpers\EmployeeHelper;
use Illuminate\Http\JsonResponse;

class FilterController extends Controller
{
    public function index(): JsonResponse
    {
        $filter = request()->has('filter') ? request()->get('filter') : null;

        $employeeHelper = (new EmployeeHelper);

        $allFilters = [
            'workModes' => $employeeHelper->workModes()->toArray(),
            'locations' => $employeeHelper->locations()->toArray(),
            'qualifications' => $employeeHelper->qualifications()->toArray(),
            'jobTypes' => $employeeHelper->jobTypes()->toArray(),
            'countries' => $employeeHelper->countries()->toArray(),
            'keySkills' => $employeeHelper->keySkills()->toArray(),
            'noticePeriods' => $employeeHelper->noticePeriods()->toArray(),
            'noOfExperiences' => $employeeHelper->noOfExperiences()->toArray(),
        ];

        if ($filter && array_key_exists($filter, $allFilters)) {
            return response()->json([
                'status' => true,
                'filters' => [$filter => $allFilters[$filter]]
            ]);
        }

        return response()->json([
            'status' => true,
            'filters' => $allFilters,
        ]);
    }
}