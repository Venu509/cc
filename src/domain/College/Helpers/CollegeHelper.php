<?php

namespace Domain\College\Helpers;

use Domain\Branch\Models\Branch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CollegeHelper
{
    public function types(): Collection
    {
        $currentRouteName = Route::currentRouteName();

        $types = collect([
            [
                'value' => 'all',
                'label' => 'All Types',
            ],
            [
                'label' => 'Government',
                'value' => 'government',
            ],
            [
                'label' => 'Institution',
                'value' => 'institution',
            ],
        ]);

        if ($currentRouteName !== 'admin.colleges.index') {
            $types = $types->filter(function ($type) {
                return $type['value'] !== 'all';
            })->values();
        }

        return $types;
    }

    public function branches(): Collection
    {
        return Branch::query()->where('added_by', Auth::user()->id)->select(
            'name as label',
            'id as value'
        )->get();
    }
}