<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class IncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Income';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                    'fill' => 'start',
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, .2)'
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
