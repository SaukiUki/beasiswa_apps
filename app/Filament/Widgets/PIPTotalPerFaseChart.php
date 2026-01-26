<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PIP;

class PIPTotalPerFaseChart extends ChartWidget
{
    protected static ?string $heading = 'Total PIP per Fase';
    protected static ?string $maxHeight = '250px';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        $data = PIP::selectRaw('fase, COUNT(*) as total')
            ->whereNotNull('fase')
            ->groupBy('fase')
            ->pluck('total', 'fase')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah PIP',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#4ade80', // green
                        '#facc15', // yellow
                        '#60a5fa', // blue
                        '#f87171', // red
                        '#c084fc', // purple
                        '#fb923c', // orange
                        '#94a3b8', // gray
                        '#2dd4bf', // teal
                        '#f472b6', // pink
                    ],
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

