<?php

namespace Domain\Vacancy\Actions;

use Domain\Skill\Models\Skill;
use Domain\Vacancy\Data\VacancyData;
use Domain\Vacancy\Models\Vacancy;
use Domain\Vacancy\Models\VacancyQuestion;
use Domain\Vacancy\Models\VacancySkill;
use Illuminate\Support\Facades\Auth;
use JsonException;
use Support\Helper\Helper;

class StoreVacancyAction
{
    use Helper;

    /**
     * @throws JsonException
     */
    public function execute(
        VacancyData $vacancyData,
        Vacancy $vacancy = new Vacancy()
    ): Vacancy {
        $vacancy->forceFill([
            'title' => $vacancyData->title,
            'salary' => $vacancyData->salary,
            'description' => $vacancyData->description,
            'years_of_experiences' => $vacancyData->yearsOfExperiences,
            'application_method' => $vacancyData->isInternalApplication ? 'internal' : 'external',
            'external_link' => $vacancyData->externalLink,
            'qualifications' => json_encode($vacancyData->qualifications, JSON_THROW_ON_ERROR),
            'benefits' => json_encode($vacancyData->benefits, JSON_THROW_ON_ERROR),
            'work_modes' => json_encode($vacancyData->workModes, JSON_THROW_ON_ERROR),
            'locations' => json_encode($vacancyData->locations, JSON_THROW_ON_ERROR),
            'no_of_openings' => $vacancyData->noOfOpenings,
            'is_walk_in_interview' => $vacancyData->isWalkInInterview,
            'start_date' => $vacancyData->startDate,
            'end_date' => $vacancyData->endDate,
            'expire_date' => $vacancyData->expireDate,
            'category_id' => $vacancyData->child['value'],
            'company_id' => $vacancyData->company,
            'modified_by' => Auth::id(),
            'salary_frequency' => $vacancyData->salaryFrequency,
            'added_by' => $vacancy->added_by ?? $vacancyData->company,
        ]);
        $vacancy->save();

        $this->updateSkills($vacancy, $vacancyData->keySkills);
        $this->updateQuestions($vacancy, $vacancyData->hasAdditionalQuestions, $vacancyData->additionalQuestions);

        return $vacancy;
    }

    protected function updateSkills(Vacancy $vacancy, array $keySkills): void
    {
        VacancySkill::query()->where('vacancy_id', $vacancy->id)->delete();

        if (!$keySkills) {
            return;
        }

        collect($keySkills)->each(function ($skill) use ($vacancy) {
            $this->storeSkill($vacancy, $skill);
        });
    }

    protected function storeSkill(Vacancy $vacancy, array $skill): void
    {
        $skillModel = Skill::query()->firstOrCreate(
            ['slug' => slugGenerator($skill['value'])],
            [
                'title' => getStringFromSlug($skill['value']),
                'slug' => slugGenerator($skill['value']),
                'modified_by' => $vacancy->company_id,
                'added_by' => $vacancy->company_id,
            ]
        );

        VacancySkill::create([
            'skill_id' => $skillModel->id,
            'vacancy_id' => $vacancy->id,
        ]);
    }

    protected function updateQuestions(Vacancy $vacancy, bool $hasAdditionalQuestions, array $additionalQuestions): void
    {
        VacancyQuestion::query()->where('vacancy_id', $vacancy->id)->delete();

        if (!$hasAdditionalQuestions) {
            return;
        }

        collect($additionalQuestions)->each(function ($question) use ($vacancy) {
            $this->storeQuestion($vacancy, $question);
        });
    }

    /**
     * @throws JsonException
     */
    protected function storeQuestion(Vacancy $vacancy, array $question): void
    {
        VacancyQuestion::create([
            'question' => $question['question'],
            'answers' => json_encode($question['answers'], JSON_THROW_ON_ERROR),
            'vacancy_id' => $vacancy->id,
        ]);
    }
}
