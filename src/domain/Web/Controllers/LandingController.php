<?php

namespace Domain\Web\Controllers;

use App\Http\Controllers\Controller;
use Domain\Banner\Actions\ListBannersAction;
use Domain\Vacancy\Actions\ListVacanciesAction;
use Domain\Vacancy\Models\Vacancy;
use Domain\Vacancy\Resources\VacancyResources;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        return view('front.index')->with([
            'vacancies' => (new ListVacanciesAction())->execute(['limit' => 6]),
        ]);
    }

    public function jobList(): View
    {
        return view('front.jobs.index');
    }

    public function jobFetch(
        ListVacanciesAction $listVacanciesAction
    ): JsonResponse {

        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 20,
            'page' => request()->has('page') ? request()->get('page') : 1,
            'search' => request()->has('search') ? request()->get('search') : null,
            'keywords' => request()->has('keywords') ? request()->get('keywords') : null,
            'locations' => request()->has('locations') ? request()->get('locations') : null,
            'qualifications' => request()->has('qualifications') ? request()->get('qualifications') : null,
            'jobTypes' => request()->has('job-types') ? request()->get('job-types') : null,
            'industries' => request()->has('industries') ? request()->get('industries') : null,
            'numberOfExperiences' => request()->has('number-of-experiences') ? request()->get('number-of-experiences') : null,
            'noticePeriod' => request()->has('notice-periods') ? request()->get('notice-periods') : null,
            'employmentStatus' => request()->has('employment-status') ? request()->get('employment-status') : null,
            'postedDate' => request()->has('posted-date') ? request()->get('posted-date') : null,
            'appliedJobs' => request()->has('applied-jobs') ? request()->get('applied-jobs') === 'yes' : null,
            'minSalary' => request()->has('min-salary') ? request()->get('min-salary') : null,
            'maxSalary' => request()->has('max-salary') ? request()->get('max-salary') : null,
            'roles' => ['candidate'],
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetch jobs',
            'jobs' => $listVacanciesAction->execute($params),
        ]);
    }

    public function jobDetail(string $slug): View
    {
        $vacancy = Vacancy::query()->where('slug', $slug)->first();

        return view('front.jobs.show')->with([
            'vacancy' =>  [
                'id' => $vacancy->id,
                'title' => $vacancy->title,
                'slug' => $vacancy->slug,
                'salary' => $vacancy->salary,
                'yearsOfExperiences' => $vacancy->years_of_experiences,
                'salaryFrequency' => getStringFromSlug($vacancy->salary_frequency),
                'category' => $vacancy->category ? [
                    'id' => $vacancy->category->id,
                    'title' => $vacancy->category->title,
                    'slug' => $vacancy->category->slug,
                    'parent' => $vacancy->category->parent ? [
                        'id' => $vacancy->category->parent->id,
                        'title' => $vacancy->category->parent->title,
                        'slug' => $vacancy->category->parent->slug,
                    ] : [],
                ] : [],
                'description' => $vacancy->description,
                'qualifications' => $vacancy->qualifications ? json_decode($vacancy->qualifications, true, 512, JSON_THROW_ON_ERROR) : [],
                'benefits' => $vacancy->benefits ? json_decode($vacancy->benefits, true, 512, JSON_THROW_ON_ERROR) : [],
                'workModes' => $vacancy->work_modes ? json_decode($vacancy->work_modes, true, 512, JSON_THROW_ON_ERROR) : [],
                'noOfOpenings' => $vacancy->no_of_openings,
                'locations' => $vacancy->locations ? json_decode($vacancy->locations, true, 512, JSON_THROW_ON_ERROR) : [],
                'expireDate' => $vacancy->expire_date,
                'isActive' => $vacancy->is_active,
                'company' => $vacancy->company ? [
                    'id' => $vacancy->company->id,
                    'name' => $vacancy->company->name,
                    'email' => $vacancy->company->email,
                    'phone' => $vacancy->company->phone,
                ] : [],
                'keySkills' => $vacancy ? collect($vacancy->skills)->map(function ($item, $index) {
                    return [
                        'index' => $index,
                        'title' => $item->title,
                    ];
                })->sortBy(function ($item) {
                    return $item['title'];
                })->values()->toArray() : [],
                'lastAccessedBy' => $vacancy->modifiedBy->name,
            ]
        ]);
    }

    public function aboutUs(): View
    {
        return view('front.about-us');
    }

    public function contactUs(): View
    {
        return view('front.contact-us');
    }
}
