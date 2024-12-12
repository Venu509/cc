<?php

namespace Domain\Dashboard\Controllers;

use Domain\Banner\Actions\ListBannersAction;
use Domain\Film\Actions\ListFilmsAction;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class LandingController extends Controller
{
    public const INDEX_ROUTE = 'front.index';

    public function index(
    ): View
    {
        return view(self::INDEX_ROUTE)->with([
            'banner' => (new ListBannersAction())->execute(isFront: true,)->first(),
            'released' => (new ListFilmsAction())->execute(isFront: true, type: 'released'),
            'upcoming' => (new ListFilmsAction())->execute(isFront: true, type: 'upcoming'),
            'inReviewMovie' => (new ListFilmsAction())->execute(isFront: true, canDisplayInHome: true)->first(),
        ]);
    }

    public function vision(
    ): View
    {
        return view('front.vision');
    }
}
