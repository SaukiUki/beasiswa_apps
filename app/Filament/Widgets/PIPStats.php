<?php

namespace App\Filament\Widgets;

use App\Models\PIP;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PIPStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make('Jumlah Penerima PIP', PIP::count())
                ->description('Total Penerima PIP')
                ->icon('heroicon-o-user')
                ->color('success'),

            Stat::make('Jumlah Sekolah', PIP::distinct('nama_sekolah')->count('nama_sekolah'))
                ->description('Sekolah unik')
                ->icon('heroicon-o-academic-cap')
                ->color('info'),

            Stat::make('Jumlah Kecamatan', PIP::distinct('kecamatan')->count('kecamatan'))
                ->description('Kecamatan tercakup')
                ->icon('heroicon-o-map')
                ->color('warning'),
        ];
    }
}
