<?php

namespace Domain\Attendance\Controllers;

use App\Http\Controllers\Controller;
use Domain\Attendance\Actions\StoreAttendanceAction;
use Domain\Attendance\Models\Attendance;
use Domain\Attendance\Requests\AttendanceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Support\Helper\Helper;
use Throwable;

class AttendanceController extends Controller
{
    use Helper;

    public function store(
        AttendanceRequest $attendanceRequest,
        StoreAttendanceAction $storeAttendanceAction
    ): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $storeAttendanceAction->execute(
                $attendanceRequest->data(),
                isset($attendanceRequest->data()->id) ? Attendance::where('id', $attendanceRequest->data()->id)->first() : new Attendance()
            );

            DB::commit();

            return redirect(route('admin.dashboard'))->withFlash([
                'type' => 'success',
                'title' => 'Attendance Saved',
            ]);
        } catch (Throwable $th) {
            DB::rollBack();

            return $this->throwable($th);
        }
    }
}
