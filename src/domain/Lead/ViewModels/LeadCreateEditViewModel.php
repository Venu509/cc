<?php

namespace Domain\Lead\ViewModels;

use Domain\Lead\Models\Lead;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Spatie\ViewModels\ViewModel;

class LeadCreateEditViewModel extends ViewModel
{
    public function __construct(
        public ?Lead $oldLead = null
    )
    {
    }

    public function lead(): array
    {
        return [
            'id' => $this->oldLead?->id,
            'title' => $this->oldLead?->title,
            'slug' => $this->oldLead?->slug,
            'type' => [
                'label' => ucfirst($this->oldLead?->type),
                'value' => $this->oldLead?->type,
            ],
            'status' => $this->oldLead?->status,
            'description' => $this->oldLead?->description,
            'isActive' => $this->oldLead?->is_active,
            'user' => $this->oldLead?->user ? [
                'id' => $this->oldLead?->user->id,
                'name' => $this->oldLead?->user->name,
                'email' => $this->oldLead?->user->email,
            ] : [],
        ];
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
