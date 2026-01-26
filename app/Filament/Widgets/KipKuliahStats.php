<?php

namespace App\Filament\Widgets;

use App\Models\KipKuliah;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KipKuliahStats extends StatsOverviewWidget
{
  protected function getStats(): array
  {
    return [
      Stat::make('Total Mahasiswa', KipKuliah::count()),
      // Stat::make('Diajukan', KipKuliah::where('status_pengajuan', 'diajukan')->count())
      //   ->color('warning'),
      // Stat::make('Diterima', KipKuliah::where('status_pengajuan', 'diterima')->count())
      //   ->color('success'),
    ];
  }
}
