<?php

namespace Domain\Skill\Actions;

use Domain\Skill\Data\SkillData;
use Domain\Skill\Models\Skill;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;

class StoreSkillAction
{
    use Helper;

    public function execute(
        SkillData $skillData,
        Skill $skill = new Skill()
    ): void {
        $skill->forceFill([
            'name' => $skillData->title,
            'slug' => slugGenerator($skillData->title),
            'modified_by' => Auth::user()->id,
            'added_by' => $skill->added_by ?? Auth::user()->id,
        ]);

        $skill->save();

        $skill->refresh();
    }
}
