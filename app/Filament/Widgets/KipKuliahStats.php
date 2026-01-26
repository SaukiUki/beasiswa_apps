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

        return [

                    // 3️⃣ Total Mahasiswa Penerima KIP
            Stat::make(
                'Total Mahasiswa Penerima KIP',
                KipKuliah::count()
            )
            ->icon('heroicon-o-academic-cap')
            ->color('success'),

            // 1️⃣ Total Laki-Laki
            Stat::make(
                'Total  KIP Laki-Laki',
                KipKuliah::where('jenis_kelamin', 'L')->count()
            )
            ->icon('heroicon-o-user')
            ->color('indigo'),

            // 2️⃣ Total Perempuan
            Stat::make(
                'Total KIP Perempuan',
                KipKuliah::where('jenis_kelamin', 'P')->count()
            )
            ->icon('heroicon-o-user-group')
            ->color('pink'),



            // 4️⃣ Total Penerima Tahun Ini
            Stat::make(
                'Total Penerima Tahun Ini',
                KipKuliah::where('tahun', $tahunIni)->count()
            )
            ->icon('heroicon-o-document-text')
            ->color('warning'),
        ];
    }
}
