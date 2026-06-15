<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class RevenueTrendChart
{
    public function build($dailySales)
    {
        $dailySales = collect($dailySales)
            ->reverse()
            ->values();

        return (new LarapexChart)
            ->lineChart()
            ->setHeight(380)
            ->setGrid('#e2e8f0', 0.3, 4)
            ->setStroke(
                width: 3,
                curve: 'smooth',
                colors: ['#E70010']
            )
            ->setMarkers(
                colors: [],
                width: 5,
                hoverSize: 8
            )
            ->setShowLegend(false)
            ->setSubtitle('Nilai dalam jutaan rupiah')
            ->addData(
                $dailySales
                    ->pluck('revenue')
                    ->map(fn ($v) => round(((float) $v) / 1000000, 1))
                    ->toArray(),
                'Revenue (Jt)'
            )
            ->setXAxis(
                $dailySales
                    ->map(fn ($row) =>
                        Carbon::parse($row->paid_date)
                            ->format('d M')
                    )
                    ->toArray()
            );
    }
}