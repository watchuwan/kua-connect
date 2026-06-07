<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusPendaftaran: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';
    case PerluRevisi = 'perlu_revisi';
    case MenungguPembayaran = 'menunggu_pembayaran';
    case VerifikasiFisik = 'verifikasi_fisik';
    case JadwalDikunci = 'jadwal_dikunci';
    case SiapIkrar = 'siap_ikrar';
    case JadwalDitugaskan = 'jadwal_ditugaskan';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::PerluRevisi => 'Perlu Revisi',
            self::MenungguPembayaran => 'Menunggu Pembayaran',
            self::VerifikasiFisik => 'Verifikasi Fisik',
            self::JadwalDikunci => 'Jadwal Dikunci',
            self::SiapIkrar => 'Siap Ikrar',
            self::JadwalDitugaskan => 'Jadwal Ditugaskan',
            self::Selesai => 'Selesai',
            self::Dibatalkan => 'Dibatalkan',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::PerluRevisi => 'danger',
            self::MenungguPembayaran => 'warning',
            self::VerifikasiFisik => 'info',
            self::JadwalDikunci => 'info',
            self::SiapIkrar => 'success',
            self::JadwalDitugaskan => 'info',
            self::Selesai => 'success',
            self::Dibatalkan => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::PerluRevisi => 'heroicon-o-exclamation-triangle',
            self::MenungguPembayaran => 'heroicon-o-banknotes',
            self::VerifikasiFisik => 'heroicon-o-user',
            self::JadwalDikunci => 'heroicon-o-lock-closed',
            self::SiapIkrar => 'heroicon-o-check-badge',
            self::JadwalDitugaskan => 'heroicon-o-calendar',
            self::Selesai => 'heroicon-o-check-circle',
            self::Dibatalkan => 'heroicon-o-x-circle',
        };
    }

    public static function transitionsForService(string $serviceSlug): array
    {
        return match ($serviceSlug) {
            'pendaftaran-nikah' => [
                self::Pending->value => [self::PerluRevisi->value, self::MenungguPembayaran->value, self::Dibatalkan->value],
                self::PerluRevisi->value => [self::Pending->value, self::Dibatalkan->value],
                self::MenungguPembayaran->value => [self::VerifikasiFisik->value, self::PerluRevisi->value, self::Dibatalkan->value],
                self::VerifikasiFisik->value => [self::JadwalDikunci->value, self::PerluRevisi->value, self::Dibatalkan->value],
                self::JadwalDikunci->value => [self::SiapIkrar->value, self::PerluRevisi->value, self::Dibatalkan->value],
                self::SiapIkrar->value => [self::JadwalDitugaskan->value, self::PerluRevisi->value, self::Dibatalkan->value],
                self::JadwalDitugaskan->value => [self::Selesai->value, self::PerluRevisi->value, self::Dibatalkan->value],
            ],
            'sertifikasi-wakaf' => [
                self::Pending->value => [self::PerluRevisi->value, self::SiapIkrar->value, self::Dibatalkan->value],
                self::PerluRevisi->value => [self::Pending->value, self::SiapIkrar->value, self::Dibatalkan->value],
                self::SiapIkrar->value => [self::Selesai->value, self::PerluRevisi->value, self::Dibatalkan->value],
            ],
            'rekomendasi-nikah-keluar' => [
                self::Pending->value => [self::PerluRevisi->value, self::Selesai->value, self::Dibatalkan->value],
                self::PerluRevisi->value => [self::Pending->value, self::Selesai->value, self::Dibatalkan->value],
            ],
            'surat-keterangan-mualaf' => [
                self::Pending->value => [self::PerluRevisi->value, self::Selesai->value, self::Dibatalkan->value],
                self::PerluRevisi->value => [self::Pending->value, self::Selesai->value, self::Dibatalkan->value],
            ],
            'kalibrasi-arah-kiblat' => [
                self::Pending->value => [self::PerluRevisi->value, self::JadwalDitugaskan->value, self::Dibatalkan->value],
                self::PerluRevisi->value => [self::Pending->value, self::Dibatalkan->value],
                self::JadwalDitugaskan->value => [self::Selesai->value, self::PerluRevisi->value, self::Dibatalkan->value],
            ],
            default => [
                self::Pending->value => [self::PerluRevisi->value, self::Selesai->value, self::Dibatalkan->value],
            ],
        };
    }

    public static function allowedTransitions(?string $currentStatus, ?string $serviceSlug): array
    {
        if (!$currentStatus || !$serviceSlug) {
            return [];
        }

        $transitions = self::transitionsForService($serviceSlug);

        return array_map(
            fn (string $v) => self::from($v),
            $transitions[$currentStatus] ?? [],
        );
    }
}
