<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; }
        .kop { text-align: center; margin-bottom: 20px; }
        .kop h2 { margin: 0; font-size: 16pt; text-transform: uppercase; }
        .kop h3 { margin: 2px 0; font-size: 13pt; text-transform: uppercase; }
        .kop p { margin: 2px 0; font-size: 11pt; }
        hr { border: 1px solid #000; margin: 15px 0; }
        .title { text-align: center; font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 20px 0; }
        .no-surat { text-align: center; font-size: 12pt; margin-bottom: 20px; }
        .content { text-align: justify; }
        .content p { margin: 8px 0; text-indent: 40px; }
        .data-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .data-table td { padding: 4px 8px; vertical-align: top; }
        .data-table td:first-child { width: 200px; }
        .signature { margin-top: 40px; text-align: right; }
        .signature p { margin: 2px 0; }
        .signature .name { font-weight: bold; text-decoration: underline; margin-top: 60px; }
        .qr { text-align: center; margin: 20px 0; }
        .footer { text-align: center; font-size: 10pt; margin-top: 20px; color: #666; }
    </style>
</head>
<body>
    <div class="kop">
        <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
        <h3>KANTOR URUSAN AGAMA KECAMATAN {{ strtoupper($data['kecamatan'] ?? '_________') }}</h3>
        <p>{{ $data['alamat_kua'] ?? 'Jl. Contoh No. 123' }}</p>
        <p>Telp. (____) ________</p>
    </div>
    <hr>

    <div class="title">SURAT REKOMENDASI NIKAH</div>
    <div class="no-surat">Nomor: {{ $pendaftaran->no_surat }}</div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini, Kepala Kantor Urusan Agama Kecamatan {{ $data['kecamatan'] ?? '_________' }}, menerangkan bahwa:</p>

        <table class="data-table">
            <tr><td>Nama Lengkap</td><td>: {{ $data['nama_lengkap'] ?? '_________' }}</td></tr>
            <tr><td>Tempat, Tanggal Lahir</td><td>: {{ ($data['tempat_lahir'] ?? '_________') . ', ' . ($data['tanggal_lahir'] ?? '_________') }}</td></tr>
            <tr><td>Pekerjaan</td><td>: {{ $data['pekerjaan'] ?? '_________' }}</td></tr>
            <tr><td>Alamat Domisili</td><td>: {{ $data['alamat_domisili_saat_ini'] ?? '_________' }}</td></tr>
        </table>

        <p>Berdasarkan permohonan yang bersangkutan dan setelah diadakan penelitian serta pemeriksaan, pada prinsipnya tidak keberatan untuk memberikan Rekomendasi Nikah kepada yang bersangkutan.</p>

        <p>Demikian Surat Rekomendasi ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature">
        <p>{{ $data['kota_terbit'] ?? '_________' }}, {{ now()->locale('id')->isoFormat('D MMMM Y') }}</p>
        <p>Kepala KUA Kecamatan {{ $data['kecamatan'] ?? '_________' }},</p>
        <br><br>
        <p class="name">{{ $data['nama_kepala_kua'] ?? '_______________________' }}</p>
        <p>{{ $data['nip_kepala_kua'] ?? 'NIP. _______________________' }}</p>
    </div>

    @if($qrCode)
    <div class="qr">
        <img src="{{ $qrCode }}" style="width: 100px; height: 100px;">
        <p style="font-size: 9pt; margin-top: 2px;">Scan untuk verifikasi dokumen</p>
    </div>
    @endif

    <div class="footer">
        <p>Dokumen ini diterbitkan secara elektronik oleh Sistem Informasi KUA-Connect</p>
    </div>
</body>
</html>
