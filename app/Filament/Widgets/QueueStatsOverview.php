<?php

namespace App\Filament\Widgets;

use App\Enums\StatusPendaftaran;
use App\Models\Pendaftaran;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QueueStatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '10s';
    protected static ?int $sort = 1;

    protected ?string $heading = 'Ringkasan Antrean';

    protected function getStats(): array
    {
        $today = today();

        return [
            Stat::make('Antrean Hari Ini', Pendaftaran::whereDate('created_at', $today)->count())
                ->description('Total pendaftaran')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('info'),
            Stat::make('Pending', Pendaftaran::whereDate('created_at', $today)->where('status', StatusPendaftaran::Pending->value)->count())
                ->description('Menunggu verifikasi')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Proses', Pendaftaran::whereDate('created_at', $today)->whereIn('status', [
                StatusPendaftaran::VerifikasiFisik->value,
                StatusPendaftaran::MenungguPembayaran->value,
                StatusPendaftaran::SiapIkrar->value,
                StatusPendaftaran::JadwalDitugaskan->value,
            ])->count())
                ->description('Dalam proses')
                ->icon('heroicon-o-user-group')
                ->color('primary'),
            Stat::make('Selesai', Pendaftaran::whereDate('created_at', $today)->where('status', StatusPendaftaran::Selesai->value)->count())
                ->description('Hari ini')
                ->icon('heroicon-o-check-badge')
                ->color('success'),
        ];
    }
}
