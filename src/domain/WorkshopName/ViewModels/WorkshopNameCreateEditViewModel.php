<?php

namespace Domain\WorkshopName\ViewModels;

use Domain\Branch\Models\Branch;
use Domain\WorkshopName\Models\WorkshopName;
use Illuminate\Support\Facades\Auth;
use Spatie\ViewModels\ViewModel;

class WorkshopNameCreateEditViewModel extends ViewModel
{
    public function __construct(
        public ?WorkshopName $oldWorkshopName = null
    )
    {
    }

    public function workshop(): array
    {
        return [
            'id' => $this->oldWorkshopName?->id,
            'name' => $this->oldWorkshopName?->name,
            'date' => $this->oldWorkshopName?->date,
            'description' => $this->oldWorkshopName?->description,
            'semester' => $this->oldWorkshopName?->semester,
            'branch' =>  $this->oldWorkshopName? [
                'label' => $this->oldWorkshopName->branch->name,
                'value' => $this->oldWorkshopName->branch->id
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
}
