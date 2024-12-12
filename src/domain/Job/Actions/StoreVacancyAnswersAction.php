<?php

namespace Domain\Job\Actions;

use App\Models\User;
use Domain\Job\Enums\ApplicantTracking;
use Domain\Job\Models\VacancyAnswer;
use Domain\Notification\Actions\StoreNotificationAction;
use Domain\Notification\Data\NotificationData;
use Domain\Vacancy\Data\UserVacancyData;
use Domain\Vacancy\Models\UserVacancy;
use Domain\Vacancy\Models\Vacancy;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;

class StoreVacancyAnswersAction
{
    use Helper;

    public function execute(
        Vacancy $vacancy,
        array $answers,
    ): void {
      foreach ($answers as $answer){

          $vacancyAnswer = new VacancyAnswer();
          $vacancyAnswer->forceFill([
              'vacancy_id' => $vacancy->id,
              'candidate_id' => Auth::user()->id,
              'vacancy_question_id' => $answer['id'],
              'answer' => $answer['answer'],
          ])->save();
      }
    }
}
