<?php

namespace Domain\Student\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\Student\Actions\HandleStudentImagesZipAction;
use Domain\Student\Actions\ListStudentsAction;
use Domain\Student\Actions\StoreStudentAction;
use Domain\Student\Imports\StudentsImport;
use Domain\Student\Models\Student;
use Domain\Student\Requests\DeleteStudentRequest;
use Domain\Student\Requests\StudentBulkUploadRequest;
use Domain\Student\Requests\StudentRequest;
use Domain\Student\Resources\StudentResources;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Support\Helper\Helper;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class StudentController extends Controller
{
    use Helper;

    public function index(
        ListStudentsAction $listStudentsAction
    ): JsonResponse
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'isFiltered' => true,
            'filterRestrictRoles' => ['admin', 'super-admin', 'master'],
            'user' => Auth::user(),
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched students',
            'students' => $listStudentsAction->execute($params),
        ]);
    }

    public function show(): JsonResponse
    {
        if (!request()->has('id')) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide the id parameter',
            ]);
        }

        $student = Student::where('id', request()->get('id'))->first();

        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not Found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched student',
            'student' => StudentResources::make($student),
        ]);
    }

    public function store(
        StudentRequest $studentRequest,
        StoreStudentAction $storeStudentAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $student = $storeStudentAction->execute(
                $studentRequest->data(),
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => __('Student :name Saved', ['name' => $studentRequest->data()->firstName]),
                'student' => StudentResources::make($student),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        StudentRequest $studentRequest,
        StoreStudentAction $storeStudentAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $student = Student::where('id', request()->get('id'))->first();

            $updatedStudent = $storeStudentAction->execute(
                $studentRequest->data(),
                $student
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => __('Student :name Updated', ['name' => $studentRequest->data()->firstName]),
                'student' => StudentResources::make($updatedStudent),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy(DeleteStudentRequest $deleteStudentRequest): JsonResponse
    {
        try {
            if($deleteStudentRequest->data()->type === 'single') {
                DB::beginTransaction();

                $student = Student::whereIn('id', $deleteStudentRequest->data()->ids)->first();

                $student->delete();

                DB::commit();

                return response()->json([
                    'status' => true,
                    'title' => 'Student Deleted',
                    'message' => __('Student :name Deleted', ['name' => $student->first_name]),
                ]);
            }

            return response()->json([
                'status' => false,
                'title' => 'Wrong API',
                'message' => "You cannot delete multiple records with single type, please provide bulk type",
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function delete(DeleteStudentRequest $deleteStudentRequest): JsonResponse
    {
        try {
            if($deleteStudentRequest->data()->type === 'bulk') {

                DB::beginTransaction();

                collect($deleteStudentRequest->data()->ids)->each(function ($student) {
                    Student::query()->where('id', $student)->delete();
                });

                DB::commit();

                return response()->json([
                    'status' => true,
                    'title' => 'Students Deleted',
                    'message' => __(':count Students are Deleted', [
                        'count' => count($deleteStudentRequest->data()->ids)
                    ]),
                ]);
            }

            return response()->json([
                'status' => false,
                'title' => 'Wrong API',
                'message' => "You cannot delete multiple records with bulk type, please provide single type",
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function bulk(
        StudentBulkUploadRequest $studentBulkUploadRequest,
        HandleStudentImagesZipAction $handleStudentImagesZipAction
    ): JsonResponse
    {
        try {
            $file = $studentBulkUploadRequest->data()->file;
            $fileType = $studentBulkUploadRequest->data()->fileUploadType;

            if($fileType === 'data') {
                Excel::import(new StudentsImport(), $file);
            } else {
                $handleStudentImagesZipAction->execute($studentBulkUploadRequest->data());
            }

            return response()->json([
                'status' => true,
                'message' => __('Bulk Student :type has been successfully uploaded', [
                    'type' => $fileType
                ]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function download(): JsonResponse
    {
        $filePath = public_path('exports/students.xlsx');

        return response()->json([
            'status' => true,
            'downloadUrl' => $filePath,
        ]);
    }
}
