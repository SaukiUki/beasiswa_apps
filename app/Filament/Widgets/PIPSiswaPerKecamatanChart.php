<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Kota;
use App\Models\PIP;

class PIPSiswaPerKecamatanChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Penerima PIP per Kecamatan';
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
    $filter = $this->filter;

    // Mapping warna per wilayah
    $warna = [
        'Kota Serang' => '#3b82f6',       // biru
        'Kabupaten Serang' => '#22c55e', // hijau
        'Kota Cilegon' => '#f97316',     // oranye
        '' => '#9ca3af',                 // semua (abu)
    ];

    $query = PIP::query()
        ->selectRaw('kecamatan, COUNT(*) as total')
        ->when($filter, function ($query) use ($filter) {

            if ($filter === 'Kota Serang') {
                $query->where('kabupaten', 'Kota Serang');
            } elseif ($filter === 'Kabupaten Serang') {
                $query->where('kabupaten', 'LIKE', 'Kab.%');
            } elseif ($filter === 'Kota Cilegon') {
                $query->where('kabupaten', 'Kota Cilegon');
            }

        })
        ->groupBy('kecamatan')
        ->orderBy('kecamatan');

    $data = $query->pluck('total', 'kecamatan')->toArray();

    return [
        'datasets' => [
            [
                'label' => 'Jumlah Siswa',
                'data' => array_values($data),
                'backgroundColor' => $warna[$filter] ?? '#9ca3af',
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
