<?php

namespace Domain\WorkshopName\Actions;

use Domain\WorkshopName\Data\WorkshopNameData;
use Domain\WorkshopName\Models\WorkshopName;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;

class StoreWorkshopNameAction
{
    use Helper;

    public function execute(
        WorkshopNameData $workshopData,
        WorkshopName $workshop = new WorkshopName()
    ): WorkshopName {
        $workshop->forceFill([
            'name' => $workshopData->name,
            'modified_by' => Auth::user()->id,
            'added_by' => $workshop->added_by !== null ? $workshop->added_by : Auth::user()->id,
        ]);

        $workshop->save();

        $workshop->refresh();

        return $workshop;
    }
}
