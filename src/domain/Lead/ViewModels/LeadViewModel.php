<?php

namespace Domain\Lead\ViewModels;

use Domain\Lead\Actions\ListLeadsAction;
use Domain\Lead\Models\Lead;
use Domain\Lead\Resources\LeadResources;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Spatie\ViewModels\ViewModel;

class LeadViewModel extends ViewModel
{
    public function __construct(
        public ?Lead $lead = null
    ) {
    }

    public function leads(): LengthAwarePaginator
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'type' => request()->has('type') ? request()->get('type') : null,
        ];

        return (new ListLeadsAction())->execute($params);
    }

    public function lead(): array|LeadResources
    {
        if ($this->lead) {
            return LeadResources::make(
                $this->lead
            );
        }

        return [];
    }

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
            [
                'label' => 'Candidate',
                'value' => 'candidate',
            ],
            [
                'label' => 'Company',
                'value' => 'company',
            ],
        ]);

        if ($currentRouteName !== 'admin.leads.index') {
            $types = $types->filter(function ($type) {
                return $type['value'] !== 'all';
            })->values();
        }

        return $types;
    }
}