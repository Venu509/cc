<?php

namespace Domain\API\Authentication\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SendOTP;
use App\Models\User;
use Domain\API\Authentication\Actions\SendOTPAction;
use Domain\API\Authentication\Actions\VerifyOTPAction;
use Domain\API\Authentication\Actions\VerifyRegisterOTPAction;
use Domain\API\Authentication\Requests\AccountVerificationCheckRequest;
use Domain\API\Authentication\Requests\LoginRequest;
use Domain\API\Authentication\Requests\RegisterRequest;
use Domain\API\Authentication\Requests\SendOTPRequest;
use Domain\API\Authentication\Requests\VerifyOTPRequest;
use Domain\Auth\Actions\StoreUserAction;
use Domain\DeviceToken\Actions\StoreDeviceTokenAction;
use Domain\DeviceToken\Data\DeviceTokenData;
use Domain\Global\Actions\GenerateOTPAction;
use Domain\Global\Resources\UserDetailsResources;
use Domain\User\Resources\UserProfileResources;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Support\Helper\Helper;
use Throwable;

class AuthController extends Controller
{
    use Helper;


    public function check(
        SendOTPRequest $sendOTPRequest,
        SendOTPAction $sendOTPAction
    ): JsonResponse
    {
        try {
            DB::beginTransaction();

            $otp = null;

            if($sendOTPRequest->data()->type === 'other') {
                $otp = (new GenerateOTPAction())->execute();

                if ($sendOTPRequest->data()->via === 'email') {
                    $credentials = ['email' => $sendOTPRequest->data()->email];
                } else {
                    $credentials = ['phone' => $sendOTPRequest->data()->phone];
                }

                $user = User::where($credentials)->first();

                if(!$user) {
                    return response()->json([
                        'status' => false,
                        'message' => 'User not found',
                    ], 422);
                }

                $user->code = $otp;

                $user->expired_at = now()->addHour();

                $user->save();

                $user->refresh();
            }

            if($sendOTPRequest->data()->type === 'register') {
                $otp = $sendOTPAction->execute(
                    $sendOTPRequest->data()
                );
            }

            if ($sendOTPRequest->data()->via === 'email' && app()->environment('production')) {
                Mail::to($sendOTPRequest->data()->email)->send(new SendOTP($otp));
            } else {
//                Mail::to($authCheckRequest->data()->email)->send(new SendOTP($otp));
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => __("We've sent the OTP to your :via (:provider)", [
                    'via' => $sendOTPRequest->data()->via,
                    'provider' => $sendOTPRequest->data()->via === 'email' ? $sendOTPRequest->data()->email : $sendOTPRequest->data()->phone,
                ]),
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->throwable($th);
        }
    }

    public function verifyOTP(
        VerifyOTPRequest $verifyOTPRequest,
        VerifyRegisterOTPAction $verifyRegisterOTPAction,
        VerifyOTPAction $verifyOTPAction
    ): JsonResponse
    {
        try {
            $otpState = false;

            if($verifyOTPRequest->data()->type === 'other') {
                if ($verifyOTPRequest->data()->via === 'email') {
                    $data = ['email' => $verifyOTPRequest->data()->email];
                } else {
                    $data = ['phone' => $verifyOTPRequest->data()->phone];
                }

                $existingOtpBuilder = User::query()
                    ->where($data)
                    ->where('code', $verifyOTPRequest->data()->code)
                    ->where('expired_at', '>=', now());

                $otpState = $verifyOTPAction->execute($existingOtpBuilder);

                if ($otpState->get('status')) {
                    $user = $otpState->get('user');

                    $token = $user->createToken('DREAMCAREER', expiresAt: now()->addDays(7))->plainTextToken;

                    return response()->json([
                        'status' => true,
                        'message' => 'Verification success.',
                        'token' => $token,
                        'user' => UserProfileResources::make($user),
                    ]);
                }

                return response()->json([
                    'status' => false,
                    'message' => 'Verification failed.',
                    'token' => null,
                ], 302);
            }

            if($verifyOTPRequest->data()->type === 'register') {
                $otpState = $verifyRegisterOTPAction->execute(
                    $verifyOTPRequest->data()
                );
            }

            return response()->json([
                'status' => $otpState->get('status'),
                'type' => $otpState->get('status') ? 'success' : 'fail',
                'title' => $otpState->get('status') ? 'OTP Verified' : 'OTP Verification failed',
                'message' => $otpState->get('message'),
            ]);
        } catch (Throwable $th) {
            return $this->throwable($th, true);
        }
    }

    public function login(
        LoginRequest $loginRequest
    ): JsonResponse
    {
        try {
            if ($loginRequest->data()->hasPassword) {
                $credentials = [
                    'username' => $loginRequest->data()->via === 'email'
                        ? $loginRequest->data()->email
                        : $loginRequest->data()->phone,
                    'password' => $loginRequest->data()->password
                ];

                if (!Auth::attempt($credentials)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Username & Password do not match our records.',
                    ], 422);
                }

                $user = Auth::user();
            } else {
                if ($loginRequest->data()->via === 'email') {
                    $credentials = ['email' => $loginRequest->data()->email];
                } else {
                    $credentials = ['phone' => $loginRequest->data()->phone];
                }

                $user = User::where($credentials)->first();

                if (!$user || ($user->code !== $loginRequest->data()->code)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid OTP or mobile number.',
                    ], 422);
                }
            }

            $token = $user->createToken('DREAMCAREER', expiresAt: now()->addDays(7))->plainTextToken;

            (new StoreDeviceTokenAction())->execute(
                (new DeviceTokenData(
                    $user->id,
                    $loginRequest->data()->deviceToken,
                    $loginRequest->data()->deviceType
                ))
            );

            if (!$user->is_active) {
                return response()->json([
                    'status' => false,
                    'title' => 'Account in review',
                    'message' => __("You don't have the right access to access this portal, because your account is still in pending"),
                ], 401);
            }

            return response()->json([
                'status' => true,
                'message' => 'Login Success',
                'token' => $token,
                'user' => UserDetailsResources::make($user),
            ]);
        } catch (Throwable $th) {
            return $this->throwable($th);
        }
    }

    public function register(
        RegisterRequest $registerRequest,
        StoreUserAction $storeUserAction
    ): JsonResponse
    {
        try {
            DB::beginTransaction();

            if(($registerRequest->data()->selectedRole === 'company') && !(new VerifyRegisterOTPAction())->otpBuilder($registerRequest->data())->first()->is_verified) {
                return response()->json([
                    'status' => false,
                    'title' => 'Please verify the OTP first',
                    'message' => __('OTP is not verified, please verify that otp to proceed'),
                ], 422);
            }

            $user = $storeUserAction->execute(
                $registerRequest->data()
            );

            DB::commit();

            return response()->json([
                'type' => 'success',
                'title' => 'User Registered',
                'message' => __('User :name Registered, please login to continue', ['name' => $user->username]),
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->throwable($th);
        }
    }

    public function accountVerification(
        AccountVerificationCheckRequest $accountVerificationCheckRequest
    ): JsonResponse
    {
        $user = User::query()->where('id', $accountVerificationCheckRequest->data()->userId)->first();

        if ($user) {
            $state = $user->is_active;

            return response()->json([
                'status' => true,
                'message' => $state ? 'Your account is been approved' : 'Your account is still in pending state',
                'state' => $state,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'User not logged in',
        ], 401);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'User logged out',
        ]);
    }
}
