<?php

namespace Domain\ProjectName\Actions;

use Domain\ProjectName\Data\ProjectNameData;
use Domain\ProjectName\Models\ProjectName;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;

class StoreProjectNameAction
{
    use Helper;

    public function execute(
        ProjectNameData $projectNameData,
        ProjectName $projectName = new ProjectName()
    ): ProjectName {
        $projectName->forceFill([
            'name' => $projectNameData->name,
            'modified_by' => Auth::user()->id,
            'added_by' => $projectName->added_by !== null ? $projectName->added_by : Auth::user()->id,
        ]);

        $projectName->save();

        $projectName->refresh();

        return $projectName;

        }
}
