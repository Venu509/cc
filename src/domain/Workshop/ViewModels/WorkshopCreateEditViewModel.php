<?php

namespace Domain\Workshop\ViewModels;

use Domain\Branch\Models\Branch;
use Domain\Workshop\Models\Workshop;
use Domain\WorkshopName\Models\WorkshopName;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\ViewModels\ViewModel;

class WorkshopCreateEditViewModel extends ViewModel
{
    public function __construct(
        public ?Workshop $oldWorkshop = null
    )
    {
    }

    public function workshop(): array
    {
        return [
            'id' => $this->oldWorkshop?->id,
            'name' =>  $this->oldWorkshop ? [
                'label' => $this->oldWorkshop->workshopName->name,
                'value' => $this->oldWorkshop->name
            ] : [],
            'startDate' => $this->oldWorkshop?->date,
            'endDate' => $this->oldWorkshop?->end_date,
            'description' => $this->oldWorkshop?->description,
            'semester' => $this->oldWorkshop?->semester,
            'branch' =>  $this->oldWorkshop? [
                'label' => $this->oldWorkshop->branch->name,
                'value' => $this->oldWorkshop->branch->id
            ]: [],
        ];
    }


    public function workshopsNames()
    {
        return WorkshopName::query()->where(function ($query) {
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

    public function branches()
    {
        return Branch::query()->where('added_by', Auth::user()->id)->select(
            'name as label',
            'id as value'
        )->get();
    }

}
