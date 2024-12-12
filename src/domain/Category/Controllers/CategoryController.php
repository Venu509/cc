<?php

namespace Domain\Category\Controllers;

use App\Http\Controllers\Controller;
use Domain\Category\Actions\ListCategoriesAction;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function fetch(
        ListCategoriesAction $listCategoriesAction
    ): JsonResponse {

        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 20,
            'search' => request()->has('search') ? request()->get('search') : null,
            'type' => request()->has('type') ? request()->get('type') : null,
            'parentId' => request()->has('parentId') ? request()->get('parentId') : null,
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetch categories',
            'categories' => $listCategoriesAction->execute($params, false),
        ]);
    }
}
