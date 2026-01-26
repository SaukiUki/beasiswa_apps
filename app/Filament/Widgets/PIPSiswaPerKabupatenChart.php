<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class PIPSiswaPerKabupatenChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Siswa per Kabupaten';
    protected static ?string $maxHeight = '250px';
    protected static ?string $pollingInterval = null;

    protected function getFilters(): array
    {
        $filters = [
            '' => 'Semua Fase',
            'all' => 'Semua Fase',
        ];
        
        // Get all fases
        $fases = \App\Models\PIP::select('fase')
            ->distinct()
            ->whereNotNull('fase')
            ->orderBy('fase')
            ->pluck('fase', 'fase')
            ->toArray();
            
        return $filters + $fases;
    }

    protected function getData(): array
    {
        $faseFilter = $this->filter;
        
        $query = \App\Models\PIP::selectRaw('kabupaten, COUNT(*) as total')
            ->when($faseFilter && $faseFilter !== 'all', function ($query) use ($faseFilter) {
                return $query->where('fase', $faseFilter);
            })
            ->groupBy('kabupaten');
            
        $data = $query->pluck('total', 'kabupaten')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa',
                    'data' => array_values($data),
                    'backgroundColor' => '#facc15',
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
