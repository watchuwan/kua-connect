<?php

namespace App\Services;

use App\Models\Pendaftaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class PdfGenerator
{
    public static function generate(Pendaftaran $pendaftaran): ?string
    {
        $slug = $pendaftaran->pelayanan?->slug;

        $template = match ($slug) {
            'rekomendasi-nikah-keluar' => 'pdf.surat-rekomendasi',
            'surat-keterangan-mualaf' => 'pdf.surat-keterangan-mualaf',
            default => null,
        };

        if (!$template) {
            return null;
        }

        $data = $pendaftaran->data ?? [];
        $qrData = url("/verifikasi/{$pendaftaran->slug}");
        $qrCodeDataUri = static::generateQrCodeDataUri($qrData);

        if (empty($pendaftaran->no_surat)) {
            $pendaftaran->no_surat = static::generateNoSurat($slug);
            $pendaftaran->saveQuietly();
        }

        $pdf = Pdf::loadView($template, [
            'pendaftaran' => $pendaftaran,
            'data' => $data,
            'qrCode' => $qrCodeDataUri,
        ]);

        $filename = static::filename($pendaftaran);

        $pdfContent = $pdf->output();

        $pendaftaran->addMediaFromString($pdfContent)
            ->usingFileName($filename)
            ->usingName($pendaftaran->no_surat)
            ->toMediaCollection('pendaftaran_files');

        return $filename;
    }

    protected static function generateQrCodeDataUri(string $data): string
    {
        try {
            $qrCode = new QrCode($data);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            return $result->getDataUri();
        } catch (\Exception $e) {
            report($e);
            return '';
        }
    }

    protected static function generateNoSurat(string $slug): string
    {
        $prefix = match ($slug) {
            'rekomendasi-nikah-keluar' => 'REK-NIKAH',
            'surat-keterangan-mualaf' => 'SK-MUALAF',
            default => 'DOC',
        };

        $year = now()->format('Y');
        $month = now()->format('m');
        $seq = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        return "{$prefix}/{$year}/{$month}/{$seq}";
    }

    protected static function filename(Pendaftaran $pendaftaran): string
    {
        $prefix = match ($pendaftaran->pelayanan?->slug) {
            'rekomendasi-nikah-keluar' => 'Surat_Rekomendasi_Nikah',
            'surat-keterangan-mualaf' => 'Surat_Keterangan_Mualaf',
            default => 'Dokumen',
        };

        $noSurat = $pendaftaran->no_surat ?? 'no_number';
        $safeNo = str_replace(['/', '\\'], '-', $noSurat);

        return "{$prefix}_{$safeNo}.pdf";
    }
}
