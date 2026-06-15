<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\HorizontalBarChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class TopCategoryChart
{
    public function build($categories)
    {
        return (new LarapexChart)
            ->horizontalBarChart()
            ->addData(
                $categories->pluck('total_sold')
                    ->map(fn ($v) => (int) $v)
                    ->toArray(),
                'Unit Terjual'
            )
            ->setXAxis(
                $categories->pluck('category_name')->toArray()
            )
            ->setColors(['#E70010']);
    }
}