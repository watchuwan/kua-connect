<?php

namespace App\Filament\Widgets;

use App\Enums\StatusPendaftaran;
use App\Models\Pendaftaran;
use Filament\Widgets\ChartWidget;

class StatusDistributionChart extends ChartWidget
{
    protected ?string $pollingInterval = '30s';

    protected function getType(): string
    {
        return 'doughnut';
    }

    public function getHeading(): ?string
    {
        return 'Distribusi Status Antrean';
    }

    protected function getData(): array
    {
        $statuses = StatusPendaftaran::cases();
        $data = [];
        $labels = [];
        $colors = [];

        foreach ($statuses as $status) {
            $count = Pendaftaran::where('status', $status->value)->count();
            if ($count > 0) {
                $data[] = $count;
                $labels[] = $status->getLabel();
                $colors[] = match ($status) {
                    StatusPendaftaran::Pending => '#f59e0b',
                    StatusPendaftaran::PerluRevisi => '#ef4444',
                    StatusPendaftaran::MenungguPembayaran => '#f59e0b',
                    StatusPendaftaran::VerifikasiFisik => '#3b82f6',
                    StatusPendaftaran::JadwalDikunci => '#8b5cf6',
                    StatusPendaftaran::SiapIkrar => '#10b981',
                    StatusPendaftaran::JadwalDitugaskan => '#3b82f6',
                    StatusPendaftaran::Selesai => '#10b981',
                    StatusPendaftaran::Dibatalkan => '#6b7280',
                };
            }
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
