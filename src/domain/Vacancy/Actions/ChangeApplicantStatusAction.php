<?php

namespace Domain\Vacancy\Actions;

use Domain\Job\Enums\ApplicantTracking;
use Domain\Vacancy\Data\ChangeApplicantStatusData;
use Domain\Vacancy\Models\UserVacancy;
use InvalidArgumentException;

class ChangeApplicantStatusAction
{
    public function execute(
        ChangeApplicantStatusData $changeApplicantStatusData,
        UserVacancy $userVacancy = new UserVacancy()
    ): UserVacancy
    {
        $userVacancy->forceFill([
            'status' => $changeApplicantStatusData->status,
            'vacancy_id' => $changeApplicantStatusData->vacancy,
            'candidate_id' => $changeApplicantStatusData->resume,
        ]);

        $statusMap = [
            'applied' => 'APPLIED',
            'shortlisted' => 'SHORTLISTED',
            'rejected' => 'REJECTED',
            'viewed' => 'VIEWED',
        ];
        $statusKey = $changeApplicantStatusData->status;
        $enumConstant = $statusMap[$statusKey] ?? 'SHORTLISTED';

        if ($enumConstant !== null) {
            $userVacancy->applicantTracking(
                constant('Domain\Job\Enums\ApplicantTracking::' . $enumConstant),
                __(':accessedBy changed your application into :state status', [
                    'accessedBy' => ucfirst(auth()->user()->name),
                    'state' => $statusKey,
                ])
            );
        } else {
            throw new InvalidArgumentException("Invalid status: $statusKey");
        }

        $userVacancy->save();
        $userVacancy->refresh();

        return $userVacancy;
    }
}