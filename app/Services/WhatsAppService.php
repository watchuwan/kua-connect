<?php

namespace App\Services;

use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function notifyJadwalKunjungan(Pendaftaran $pendaftaran): void
    {
        $data = $pendaftaran->data ?? [];
        $noHp = $data['no_hp_kontak'] ?? null;

        if (!$noHp) {
            Log::warning('WhatsApp: No HP kontak tidak ditemukan', ['pendaftaran_id' => $pendaftaran->id]);
            return;
        }

        $jadwal = $pendaftaran->jadwal_kedatangan
            ? $pendaftaran->jadwal_kedatangan->setTimezone('Asia/Jayapura')->locale('id')->isoFormat('D MMMM Y [pukul] HH:mm')
            : 'akan diinformasikan lebih lanjut';

        $message = static::buildMessage($pendaftaran, $jadwal);

        static::send($noHp, $message);
    }

    protected static function buildMessage(Pendaftaran $pendaftaran, string $jadwal): string
    {
        $data = $pendaftaran->data ?? [];
        $namaTempat = $data['nama_tempat_ibadah'] ?? 'tempat ibadah';

        return implode("\n", [
            "*PEMBERITAHUAN JADWAL KALIBRASI ARAH KIBLAT*",
            "",
            "Assalamu'alaikum Wr. Wb.",
            "",
            "Dengan ini kami informasikan bahwa jadwal kalibrasi arah kiblat untuk:",
            "",
            "Tempat: {$namaTempat}",
            "Nomor Antrean: {$pendaftaran->nomor_antrean}",
            "",
            "Akan dilaksanakan pada:",
            "*{$jadwal}*",
            "",
            "Mohon kesediaan pengurus untuk hadir dan mendampingi petugas.",
            "",
            "Terima kasih.",
            "",
            "Wassalamu'alaikum Wr. Wb.",
            "KUA Kecamatan {$data['kecamatan'] ?? ''}",
        ]);
    }

    protected static function send(string $noHp, string $message): void
    {
        $apiUrl = config('services.whatsapp.api_url');
        $apiKey = config('services.whatsapp.api_key');

        if (!$apiUrl || !$apiKey) {
            Log::warning('WhatsApp: API tidak dikonfigurasi. Pesan tidak terkirim.', [
                'to' => $noHp,
                'message' => $message,
            ]);
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
            ])->post($apiUrl, [
                'target' => $noHp,
                'message' => $message,
            ]);

            if (!$response->successful()) {
                Log::error('WhatsApp: Gagal mengirim pesan', [
                    'to' => $noHp,
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp: Error saat mengirim pesan', [
                'to' => $noHp,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
