<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Order;

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

    public function exportCsv(Request $request)
{
    $from = $request->filled('from')
        ? Carbon::parse($request->from)
        : now()->subDays(29);

    $to = $request->filled('to')
        ? Carbon::parse($request->to)
        : now();

    $orders = Order::query()
        ->whereNotNull('paid_at')
        ->whereDate('paid_at', '>=', $from)
        ->whereDate('paid_at', '<=', $to)
        ->get();

    $filename = sprintf(
    'warudgeartech-sales-report-%s-to-%s.csv',
    $from->format('Y-m-d'),
    $to->format('Y-m-d')
    );

    return response()->streamDownload(function () use ($orders) {

        $handle = fopen('php://output', 'w');

        fputcsv($handle, [
            'Order Number',
            'Customer',
            'Email',
            'Phone',
            'Status',
            'Total',
            'Paid At',
        ]);

        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->order_number,
                $order->customer_name,
                $order->email,
                $order->phone,
                $order->status->value,
                $order->total,
                $order->paid_at,
            ]);
        }

        fclose($handle);
    }, $filename);
}
}
