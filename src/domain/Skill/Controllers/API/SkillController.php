<?php

namespace Domain\Skill\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\Skill\Actions\ListSkillsAction;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    public function index():JsonResponse
    {
        $params = [
            'search' => request()->has('search') ? request()->get('search') : null,
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'page' => request()->has('page') ? request()->get('page') : 1,
        ];

        return response()->json([
            'status' => true,
            'skills' => (new ListSkillsAction())->execute($params, true)
        ]);
    }
}
