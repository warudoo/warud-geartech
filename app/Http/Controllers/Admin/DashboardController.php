<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\ReportService;

class DashboardController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
    ) {
    }

    public function __invoke()
    {
        return view('admin.dashboard', [
            'summary' => $this->reportService->summary(),
            'totalUsers' => User::query()->count(),
            'totalProducts' => Product::query()->count(),
            'recentOrders' => Order::query()->with('user')->latest()->take(6)->get(),
        ]);
    }
}
