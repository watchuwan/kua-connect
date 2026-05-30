<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftaran;
use Filament\Widgets\ChartWidget;

class QueueTrendChart extends ChartWidget
{
    protected ?string $pollingInterval = "60s";
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = "full";
    protected ?string $maxHeight = "300px";

    protected function getType(): string
    {
        return "line";
    }

    public function getHeading(): ?string
    {
        return "Tren Antrean (14 Hari)";
    }

    protected function getData(): array
    {
        $days = 14;
        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->translatedFormat("d M");
            $data[] = Pendaftaran::whereDate("created_at", $date)->count();
        }

        return [
            "datasets" => [
                [
                    "label" => "Pendaftaran",
                    "data" => $data,
                    "borderColor" => "#0C226F",
                    "backgroundColor" => "rgba(12, 34, 111, 0.1)",
                    "fill" => true,
                    "tension" => 0.3,
                ],
            ],
            "labels" => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            "plugins" => [
                "legend" => [
                    "display" => false,
                ],
            ],
            "scales" => [
                "y" => [
                    "beginAtZero" => true,
                    "ticks" => [
                        "stepSize" => 1,
                    ],
                ],
            ],
        ];
    }
}
