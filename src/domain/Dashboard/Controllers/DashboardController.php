<?php

namespace Domain\Dashboard\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Domain\Dashboard\ViewModels\DashboardViewModel;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request): Response
    {
        $viewModel = new DashboardViewModel();

        return Inertia::render('Dashboard', $viewModel);
    }
}
