<?php

namespace App\Filament\Widgets;

use App\Models\KipKuliah;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KipKuliahStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $tahunIni = now()->year;

        // Hitung sekali (lebih ringan)
        $total = KipKuliah::count();
        $laki = KipKuliah::where('jenis_kelamin', 'L')->count();
        $perempuan = KipKuliah::where('jenis_kelamin', 'P')->count();
        $tahunIniCount = KipKuliah::where('tahun', $tahunIni)->count();

        return [

            // Total penerima
            Stat::make('Total Mahasiswa Penerima KIP', $total)
                ->icon('heroicon-o-academic-cap')
                ->color('success'),

            // Laki-laki
            Stat::make('Total KIP Laki-Laki', $laki)
                ->icon('heroicon-o-user')
                ->color('indigo'),

            // Perempuan
            Stat::make('Total KIP Perempuan', $perempuan)
                ->icon('heroicon-o-user-group')
                ->color('pink'),

            // Tahun berjalan
            Stat::make("Total Penerima Tahun {$tahunIni}", $tahunIniCount)
                ->icon('heroicon-o-calendar')
                ->color('warning'),
        ];
    }
}
