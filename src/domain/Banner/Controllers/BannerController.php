<?php

namespace Domain\Banner\Controllers;

use App\Http\Controllers\Controller;
use Domain\Banner\Actions\ListBannersAction;
use Domain\Banner\Actions\StoreBannerAction;
use Domain\Banner\Models\Banner;
use Domain\Banner\Requests\BannerRequest;
use Domain\Banner\ViewModels\BannerViewModel;
use Domain\Global\Actions\DestroyModelAction;
use Domain\Global\Actions\ManageStatusAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class BannerController extends Controller
{
    public const INDEX_ROUTE = 'admin.banners.index';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(
        ListBannersAction $listBannersAction
    ): InertiaResponse {
        $viewModel = new BannerViewModel(
            20,
            $listBannersAction
        );

        return Inertia::render('Banners/Index', $viewModel);
    }

    public function store(
        BannerRequest $bannerRequest,
        StoreBannerAction $storeBannerAction
    ): RedirectResponse {
        $storeBannerAction->execute(
            $bannerRequest->data()
        );

        return redirect(route(self::INDEX_ROUTE))->withFlash([
            'type' => 'success',
            'title' => 'Banner Saved',
            'message' => __('Banner :name Saved', ['name' => $bannerRequest->data()->title]),
        ]);
    }

    public function show(
        Banner $banner,
        ListBannersAction $listBannersAction
    ): InertiaResponse {
        $viewModel = new BannerViewModel(
            20,
            $listBannersAction,
            $banner
        );

        return Inertia::render('Banners/Index', $viewModel);
    }

    public function update(
        Banner $banner,
        BannerRequest $bannerRequest,
        StoreBannerAction $storeBannerAction
    ): RedirectResponse {
        $storeBannerAction->execute(
            $bannerRequest->data(),
            $banner,
        );

        return redirect(route(self::INDEX_ROUTE))->withFlash([
            'type' => 'success',
            'title' => 'Banner Updated',
            'message' => __('Banner :name Updated', ['name' => $bannerRequest->data()->title]),
        ]);
    }

    public function status(
        Banner $banner,
        Request $request,
        ManageStatusAction $manageStatusAction,
    ): RedirectResponse {
        $manageStatusAction->execute($banner, $request, target: 'isActive');

        return redirect(route(self::INDEX_ROUTE))->withFlash([
            'type' => 'success',
            'title' => 'Banner Updated',
            'message' => __('Banner :name Status Updated', ['name' => $banner->title]),
        ]);
    }

    public function destroy(
        Banner $banner,
        DestroyModelAction $destroyModelAction
    ): RedirectResponse {
        $destroyModelAction->execute($banner);

        return redirect(route(self::INDEX_ROUTE))->withFlash([
            'type' => 'success',
            'title' => 'Banner Deleted',
            'message' => __('Banner :name Delete', ['name' => $banner->title]),
        ]);
    }
}
