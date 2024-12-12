<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\API\Authentication\Requests\RegisterRequest;
use Domain\Auth\Actions\StoreUserAction;
use Domain\Global\Helpers\EmployeeHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Support\Helper\Helper;
use Throwable;

class RegisterController extends Controller
{

//    use RegistersUsers;
    use Helper;

    public function rules():JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new RegisterRequest())->validations()
        ]);
    }


    public function showRegistrationForm(): InertiaResponse
    {
        $role = manageSession();

        return Inertia::render('Auth/Register', [
            'selectedRole' => $role,
            'noticePeriods' => (new EmployeeHelper())->noticePeriods(),
            'keySkills' => (new EmployeeHelper())->keySkills(),
            'userPreferredLocations' => (new EmployeeHelper())->userPreferredLocations(),
        ]);
    }

    public function register(
        RegisterRequest $registerRequest,
        StoreUserAction $storeUserAction
    ): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $user = $storeUserAction->execute(
                $registerRequest->data()
            );

            $role = $user->roles()->first()->name;
            $route = 'admin.login';

            if(in_array($role, ['government', 'institution', 'candidate', 'company'], true)) {
                $route = 'login';
            }

            if($role === 'marketing') {
                $route = 'marketing.login';
            }

            DB::commit();

            return redirect()->route($route)->withFlash([
                'type' => 'success',
                'title' => 'User Registered',
                'message' => __('User :name Registered, please login to continue', ['name' => $user->username]),
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->throwable($th);
        }
    }
}
