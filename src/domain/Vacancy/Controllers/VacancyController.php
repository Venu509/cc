<?php

namespace Domain\Vacancy\Controllers;

use App\Http\Controllers\Controller;
use Domain\Global\Actions\NotifyCandidateAction;
use Domain\Vacancy\Actions\StoreVacancyAction;
use Domain\Vacancy\Models\Vacancy;
use Domain\Vacancy\Requests\VacancyRequest;
use Domain\Vacancy\ViewModels\VacancyCreateEditViewModel;
use Domain\Vacancy\ViewModels\VacancyViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Support\Helper\Helper;
use Throwable;

class VacancyController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'admin.vacancies.index';

    public function rules():JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new VacancyRequest())->validations()
        ]);
    }

    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new VacancyViewModel(
            20,
        );

        return Inertia::render('Vacancies/Index', $viewModel);
    }

    public function create(): InertiaResponse
    {
        $viewModel = new VacancyCreateEditViewModel();

        return Inertia::render('Vacancies/Create', $viewModel);
    }

    public function show(Vacancy $vacancy): InertiaResponse
    {
        $viewModel = new VacancyCreateEditViewModel($vacancy);

        return Inertia::render('Vacancies/Create', $viewModel);
    }

    public function store(
        VacancyRequest $vacancyRequest,
        StoreVacancyAction $storeVacancyAction,
        NotifyCandidateAction $notifyCandidateAction
    ) {
        try {
            DB::beginTransaction();

            $vacancy = $storeVacancyAction->execute(
                $vacancyRequest->data(),
            );

            $notifyCandidateAction->execute($vacancy, $vacancyRequest->data());

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Vacancy Saved',
                'message' => __('Vacancy :name Saved', ['name' => $vacancyRequest->data()->title]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        Vacancy $vacancy,
        VacancyRequest $vacancyRequest,
        StoreVacancyAction $storeVacancyAction,
        NotifyCandidateAction $notifyCandidateAction
    ) {
        try {
            DB::beginTransaction();

            $updatedVacancy = $storeVacancyAction->execute(
                $vacancyRequest->data(),
                $vacancy
            );

            $notifyCandidateAction->execute($updatedVacancy, $vacancyRequest->data());

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Vacancy Updated',
                'message' => __('Vacancy :name Updated', ['name' => $vacancyRequest->data()->title]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy(Vacancy $vacancy)
    {
        try {
            DB::beginTransaction();

            $vacancy->delete();

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Vacancy Deleted',
                'message' => __('Vacancy :name Deleted', ['name' => $vacancy->first_name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }
}
