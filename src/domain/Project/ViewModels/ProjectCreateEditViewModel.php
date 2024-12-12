<?php

namespace Domain\Project\ViewModels;

use Domain\Branch\Models\Branch;
use Domain\Project\Models\Project;
use Domain\ProjectName\Models\ProjectName;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\ViewModels\ViewModel;

class ProjectCreateEditViewModel extends ViewModel
{
    public function __construct(
        public ?Project $oldProject = null
    )
    {
    }

    public function project(): array
    {
        return [
            'id' => $this->oldProject?->id,
            'name' => $this->oldProject ? [
                'value' => $this->oldProject->name,
                'label' => $this->oldProject->projectName->name
            ] : [],
            'type' => $this->oldProject?->type,
            'startDate' => $this->oldProject?->date,
            'endDate' => $this->oldProject?->end_date,
            'description' => $this->oldProject?->description,
            'semester' => $this->oldProject?->semester,
            'branch' =>  $this->oldProject? [
                'label' => $this->oldProject->branch->name,
                'value' => $this->oldProject->branch->id
            ]: [],
        ];
    }

    public function branches()
    {
        return Branch::query()->where('added_by', Auth::user()->id)->select(
            'name as label',
            'id as value'
        )->get();
    }

    public function projectsNames(): Collection|array
    {
        return ProjectName::query()->where(function ($query) {
                $query->where('added_by', Auth::user()->id)
                    ->orWhereHas('addedBy', function ($query) {
                        $query->whereHas('roles', function ($query) {
                            $query->whereIn('name', ['admin', 'super-admin', 'master']);
                        });
                    });
            })
            ->select('name as label', 'id as value')
            ->get();
    }
}
