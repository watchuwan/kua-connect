<?php

namespace App\Filament\Widgets;

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
        $waiting = Pendaftaran::where('status', 'waiting')->count();
        $serving = Pendaftaran::where('status', 'serving')->count();
        $done = Pendaftaran::where('status', 'done')->count();
        $skipped = Pendaftaran::where('status', 'skipped')->count();

        return [
            'datasets' => [
                [
                    'data' => [$waiting, $serving, $done, $skipped],
                    'backgroundColor' => ['#f59e0b', '#3b82f6', '#10b981', '#6b7280'],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => ['Menunggu', 'Sedang Dilayani', 'Selesai', 'Dilewati'],
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
