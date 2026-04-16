<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
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
            'totalBuyers' => User::query()->where('role', UserRole::USER)->count(),
            'totalProducts' => Product::query()->count(),
            'activeProducts' => Product::query()->where('is_active', true)->count(),
            'recentOrders' => Order::query()->with('user')->latest()->take(5)->get(),
            'lowStockProducts' => Product::query()
                ->with('category')
                ->where('stock', '<=', 5)
                ->orderBy('stock')
                ->take(5)
                ->get(),
        ]);
    }
}
