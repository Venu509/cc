<?php

namespace Domain\Attendance\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\Attendance\Actions\StoreAttendanceAction;
use Domain\Attendance\Models\Attendance;
use Domain\Attendance\Requests\AttendanceRequest;
use Domain\Attendance\Resources\AttendanceResources;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Support\Helper\Helper;
use Throwable;

class AttendanceController extends Controller
{
    use Helper;

    public function index(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched attendance details',
            'attendance' => $this->attendance(),
        ]);
    }

    public function store(
        AttendanceRequest $attendanceRequest,
        StoreAttendanceAction $storeAttendanceAction
    ): JsonResponse
    {
        try {
            DB::beginTransaction();

            $attendance = $storeAttendanceAction->execute(
                $attendanceRequest->data(),
                isset($attendanceRequest->data()->id) ? Attendance::where('id', $attendanceRequest->data()->id)->first() : new Attendance()
            );

            DB::commit();

            return response()->json([
                'type' => 'success',
                'title' => 'Attendance Saved',
                'attendance' => AttendanceResources::make($attendance)
            ]);
        } catch (Throwable $th) {
            DB::rollBack();

            return $this->throwable($th);
        }
    }
}
