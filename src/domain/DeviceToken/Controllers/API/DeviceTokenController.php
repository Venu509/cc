<?php

namespace Domain\DeviceToken\Controllers\API;

use Throwable;
use Support\Helper\Helper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Domain\DeviceToken\Requests\DeviceTokenRequest;
use Domain\DeviceToken\Actions\StoreDeviceTokenAction;

class DeviceTokenController extends Controller
{
    use Helper;

    public function store(
        DeviceTokenRequest $deviceTokenRequest,
        StoreDeviceTokenAction $storeDeviceTokenAction
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $storeDeviceTokenAction->execute($deviceTokenRequest->data());

            DB::commit();

            return  response()->json([
                'status' => true,
                'message' => 'Device Token saved',
            ]);
        } catch (Throwable $th) {
            DB::rollBack();

            return $this->throwable($th);
        }
    }
}
