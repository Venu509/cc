<?php

namespace Domain\Student\Controllers;

use Domain\Student\Actions\HandleStudentImagesZipAction;
use Domain\Student\Actions\StoreStudentAction;
use Domain\Student\Actions\StoreStudentImagesAction;
use Domain\Student\Exports\StudentsExport;
use Domain\Student\Imports\StudentsImport;
use Domain\Student\Models\Student;
use Domain\Student\Requests\StudentBulkUploadRequest;
use Domain\Student\Requests\StudentRequest;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Domain\Student\Actions\ListStudentsAction;
use Inertia\Response as InertiaResponse;
use Domain\Student\ViewModels\StudentViewModel;
use Domain\Student\ViewModels\StudentCreateEditViewModel;
use Maatwebsite\Excel\Facades\Excel;
use Support\Helper\Helper;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class StudentController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'admin.students.index';

    public function rules():JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new StudentRequest())->validations()
        ]);
    }

    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new StudentViewModel(
            20,
        );

        return Inertia::render('Students/Index', $viewModel);
    }

    public function create(): InertiaResponse
    {
        $viewModel = new StudentCreateEditViewModel();

        return Inertia::render('Students/Create', $viewModel);
    }

    public function uploading(): InertiaResponse
    {
        return Inertia::render('Students/Uploading');
    }

    public function show(Student $student): InertiaResponse
    {
        $viewModel = new StudentCreateEditViewModel($student);

        return Inertia::render('Students/Create', $viewModel);
    }

    public function store(
        StudentRequest $studentRequest,
        StoreStudentAction $storeStudentAction,
    ) {
        try {
            DB::beginTransaction();

            $storeStudentAction->execute(
                $studentRequest->data(),
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Student Saved',
                'message' => __('Student :name Saved', ['name' => $studentRequest->data()->firstName]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        Student $student,
        StudentRequest $studentRequest,
        StoreStudentAction $storeStudentAction,
    ) {
        try {
            DB::beginTransaction();

            $storeStudentAction->execute(
                $studentRequest->data(),
                $student
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Student Updated',
                'message' => __('Student :name Updated', ['name' => $studentRequest->data()->firstName]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy( Student $student)
    {
        try {
            DB::beginTransaction();

            $student->delete();

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Student Deleted',
                'message' => __('Student :name Deleted', ['name' => $student->first_name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();

            collect($request->get('selectedIds'))->each(function ($student) {
                Student::query()->where('id', $student)->delete();
            });

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Students are deleted',
                'message' => __('Selected Students are deleted'),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function bulk(
        StudentBulkUploadRequest $studentBulkUploadRequest,
        HandleStudentImagesZipAction $handleStudentImagesZipAction
    )
    {
        try {
            $file = $studentBulkUploadRequest->data()->file;
            $fileType = $studentBulkUploadRequest->data()->fileUploadType;

            if($fileType === 'data') {
                Excel::import(new StudentsImport(), $file);
            } else {
                $handleStudentImagesZipAction->execute($studentBulkUploadRequest->data());
            }

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'File been uploaded',
                'message' => __('Bulk Student :type has been successfully uploaded', [
                    'type' => $fileType
                ]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }


    public function download(): BinaryFileResponse
    {
        $filePath = public_path('exports/students.xlsx');

        return response()->download($filePath);
    }
}
