<?php

namespace App\Filament\Widgets;

use App\Models\Pelayanan;
use Filament\Widgets\ChartWidget;

class TopServicesChart extends ChartWidget
{
    protected ?string $pollingInterval = "120s";

    protected function getType(): string
    {
        return "bar";
    }

    public function getHeading(): ?string
    {
        return "Layanan Terpopuler";
    }

    protected function getData(): array
    {
        $services = Pelayanan::where("aktif", true)
            ->withCount("pendaftaran")
            ->orderByDesc("pendaftaran_count")
            ->take(10)
            ->get();

        return [
            "datasets" => [
                [
                    "label" => "Pendaftaran",
                    "data" => $services->pluck("pendaftaran_count")->toArray(),
                    "backgroundColor" => [
                        "#0C226F",
                        "#1a3380",
                        "#2a4a99",
                        "#3a5199",
                        "#4a69b3",
                        "#5a80cc",
                        "#7a8bbf",
                        "#9aa6d4",
                        "#bbc3e6",
                        "#dde3ef",
                    ],
                    "borderRadius" => 4,
                ],
            ],
            "labels" => $services->pluck("nama_pelayanan")->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            "indexAxis" => "y",
            "plugins" => [
                "legend" => [
                    "display" => false,
                ],
            ],
            "scales" => [
                "x" => [
                    "beginAtZero" => true,
                    "ticks" => [
                        "stepSize" => 1,
                    ],
                ],
            ],
        ];
    }
}
