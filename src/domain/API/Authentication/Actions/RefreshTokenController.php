<?php

namespace Domain\API\Authentication\Actions;

use Domain\Global\Resources\UserDetailsResources;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;

class RefreshTokenController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        PersonalAccessToken::findToken(request()->bearerToken())->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully token generated',
            'token' => $request->user()->createToken('DREAMCAREER', ['*'], now()->addDays(7))->plainTextToken,
            'user' => UserDetailsResources::make(auth()->user()),
        ], 201);
    }
}
