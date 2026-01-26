<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Kota;
use App\Models\PIP;

class PIPSiswaPerKecamatanChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Siswa per Kecamatan';
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = null;

    protected function getFilters(): array
    {
        $filters = ['' => 'Semua Kota'];
        $kotas = Kota::pluck('nama_kota', 'nama_kota')->toArray();
        return $filters + $kotas;
    }

    protected function getData(): array
    {
        $kotaFilter = $this->filter;
        
        $query = PIP::selectRaw('kecamatan, COUNT(*) as total')
            ->when($kotaFilter, function ($query) use ($kotaFilter) {
                return $query->where('kabupaten', $kotaFilter);
            })
            ->groupBy('kecamatan');
            
        $data = $query->pluck('total', 'kecamatan')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa',
                    'data' => array_values($data),
                    'backgroundColor' => '#4ade80',
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
