<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * The dashboard service instance.
     *
     * @var \App\Services\DashboardService
     */
    protected $dashboardService;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\DashboardService  $dashboardService
     * @return void
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $statistics = $this->dashboardService->getStatistics();
        $recentItems = $this->dashboardService->getRecentItems();

        return view('dashboard', compact('statistics', 'recentItems'));
    }
}
