<?php

namespace Domain\Workshop\Actions;

use Domain\Workshop\Data\WorkshopData;
use Domain\Workshop\Models\Workshop;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;

class StoreWorkshopAction
{
    use Helper;

    public function execute(
        WorkshopData $workshopData,
        Workshop $workshop = new Workshop()
    ): Workshop {
        $workshop->forceFill([
            'name' => $workshopData->name['value'],
            'branch_id' => $workshopData->branch['value'],
            'semester' => $workshopData->semester,
            'description' => $workshopData->description,
            'date' => $workshopData->date,
            'end_date' => $workshopData->endDate,
            'modified_by' => Auth::user()->id,
            'added_by' => $workshop->added_by !== null ? $workshop->added_by : Auth::user()->id,
        ]);

        $workshop->save();

        $workshop->refresh();

        return $workshop;
    }
}
