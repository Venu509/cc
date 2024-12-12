<?php

namespace Domain\Project\Actions;

use Domain\Project\Data\ProjectData;
use Domain\Project\Models\Project;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;

class StoreProjectAction
{
    use Helper;

    public function execute(
        ProjectData $projectData,
        Project $project = new Project()
    ): Project {
        $project->forceFill([
            'name' => $projectData->name['value'],
            'type' => $projectData->type,
            'description' => $projectData->description,
            'branch_id' => $projectData->branch['value'],
            'semester' => $projectData->semester,
            'date' => $projectData->date,
            'end_date' => $projectData->endDate,
            'modified_by' => Auth::user()->id,
            'added_by' => $project->added_by ?? Auth::user()->id,
        ]);

        $project->save();

        $project->refresh();

        return $project;
    }
}
