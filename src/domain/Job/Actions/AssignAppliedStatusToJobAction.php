<?php

namespace Domain\Job\Actions;

use Domain\Job\Data\AssignAppliedStatusToJobData;
use Domain\Vacancy\Actions\ChangeApplicantStatusAction;
use Domain\Vacancy\Data\ChangeApplicantStatusData;
use Domain\Vacancy\Models\UserVacancy;
use Domain\Vacancy\Models\Vacancy;

class AssignAppliedStatusToJobAction
{
    public function execute(
        AssignAppliedStatusToJobData $assignAppliedStatusToJobData,
        Vacancy $vacancy
    ): void
    {
        $userVacancy = UserVacancy::where('candidate_id', $assignAppliedStatusToJobData->user)
            ->where('vacancy_id', $vacancy->id)
            ->first();

        if (!$userVacancy) {
            (new ChangeApplicantStatusAction())->execute(
                new ChangeApplicantStatusData(
                    $vacancy->id,
                    $assignAppliedStatusToJobData->user,
                    false,
                    'applied',
                    'pending'
                )
            );
        }
    }
}