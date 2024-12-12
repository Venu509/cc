<?php

namespace Domain\Vacancy\ViewModels;

use Domain\User\Actions\ListCandidatesAction;
use Domain\Vacancy\Models\Vacancy;
use Domain\Vacancy\Resources\VacancyResources;
use Illuminate\Pagination\LengthAwarePaginator;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spatie\ViewModels\ViewModel;

class AppliedCandidateViewModel extends ViewModel
{
    public function __construct(
        public ?int $perPage = 10,
        public ?Vacancy $vacancy = null
    ) {
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws JsonException
     */
    public function candidates(): LengthAwarePaginator
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'tab' => request()->has('tab') ? request()->get('tab') : 'pending',
            'roles' => ['candidate'],
            'vacancyId' => $this->vacancy->id,
            'requiredSkills' => $this->requiredSkills(),
        ];

        $statusMap = [
            'pending' => ['applied', 'viewed'],
            'shortlisted' => ['shortlisted'],
            'rejected' => ['rejected'],
        ];

        $params['tab'] = $statusMap[$params['tab']] ?? [];

        return (new ListCandidatesAction())->execute($params);
    }

    public function vacancy(): VacancyResources
    {
        return VacancyResources::make($this->vacancy);
    }

    /**
     * @throws JsonException
     */
    private function requiredSkills(): array
    {
        return array_merge(
            $this->vacancy->skills?->pluck('title')->toArray(),
            [$this->vacancy->title],
            [$this->vacancy->description],
//            [$this->vacancy->slug],
//            [$this->vacancy->location],
//            [$this->vacancy->category->title],
//            $this->vacancy->qualifications ? json_decode($this->vacancy->qualifications, true, 512, JSON_THROW_ON_ERROR) : [],
//            $this->vacancy->benefits ? json_decode($this->vacancy->benefits, true, 512, JSON_THROW_ON_ERROR) : [],
//            $this->vacancy->work_modes ? json_decode($this->vacancy->work_modes, true, 512, JSON_THROW_ON_ERROR) : [],
        );
    }

}
