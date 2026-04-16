<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
    ) {
    }

    public function index(Request $request)
    {
        $from = $request->filled('from') ? Carbon::parse($request->string('from')->value()) : now()->subDays(29)->startOfDay();
        $to = $request->filled('to') ? Carbon::parse($request->string('to')->value()) : now()->endOfDay();

        return view('admin.reports.index', [
            'summary' => $this->reportService->summary($from, $to),
            'from' => $from,
            'to' => $to,
        ]);
    }
}
