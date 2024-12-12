<?php

namespace Domain\Dashboard\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Blog\Actions\ListBlogsAction;
use Domain\Package\Actions\ListPlansAction;
use Domain\Event\Actions\ListEventsAction;
use Domain\Story\Actions\ListStoriesAction;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Domain\Testimonial\Actions\ListTestimonialsAction;

class StarterController extends Controller
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function index(
        ListStoriesAction $listStoriesAction,
        ListEventsAction $listEventsAction,
        ListTestimonialsAction $listTestimonialsAction,
        ListBlogsAction $listBlogsAction,
        ListPlansAction $listPlansAction,
    ): JsonResponse {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched starter page',
            'stories' => $listStoriesAction->execute(true, $params),
            'upcoming-events' => $listEventsAction->execute(true, $params, true),
            'testimonials' => $listTestimonialsAction->execute(true, $params),
            'blogs' => $listBlogsAction->execute(true),
            'plans' => $listPlansAction->execute(true, [
                'type' => 'special',
            ]),
        ]);
    }
}
