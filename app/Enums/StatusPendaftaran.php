<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum StatusPendaftaran: string implements HasLabel
{
    case Waiting = 'waiting';
    case Serving = 'serving';
    case Done = 'done';
    case Skipped = 'skipped';

    public function getLabel(): string
    {
        return match ($this) {
            self::Waiting => 'Menunggu',
            self::Serving => 'Sedang Dilayani',
            self::Done => 'Selesai',
            self::Skipped => 'Dilewati',
        };
    }
}
