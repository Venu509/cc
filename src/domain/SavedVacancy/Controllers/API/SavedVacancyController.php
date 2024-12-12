<?php

namespace Domain\SavedVacancy\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\SavedVacancy\Actions\StoreSavedVacancyAction;
use Domain\SavedVacancy\Models\SavedVacancy;
use Domain\SavedVacancy\Requests\SavedVacancyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Support\Helper\Helper;
use Throwable;

class SavedVacancyController extends Controller
{
    use Helper;

    public function store(
        SavedVacancyRequest $savedJobRequest,
        StoreSavedVacancyAction $storeSavedVacancyAction,
    ): JsonResponse {
        $savedVacancy = SavedVacancy::where('candidate_id', auth()->id())
            ->where('vacancy_id', $savedJobRequest->data()->vacancyId);

        if ($savedVacancy->exists()){

            $savedVacancy->first()->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Removing success',
                'message' => __('Vacancy removed successfully from saved list'),
            ]);
        }
        try {
            DB::beginTransaction();

            $storeSavedVacancyAction->execute(
                $savedJobRequest->data(),
            );

            DB::commit();

            return response()->json([
                'type' => 'success',
                'title' => 'Saved job successfully',
                'message' => __('Vacancy added to saved list successfully'),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }
}
