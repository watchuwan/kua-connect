<?php

namespace Database\Seeders;

use App\Models\FormField;
use App\Models\Pelayanan;
use Illuminate\Database\Seeder;

class FormFieldSeeder extends Seeder
{
    public function run(): void
    {
        $nikah = Pelayanan::where('slug', 'pendaftaran-nikah')->first();
        $wakaf = Pelayanan::where('slug', 'sertifikasi-wakaf')->first();
        $rekom = Pelayanan::where('slug', 'rekomendasi-nikah-keluar')->first();
        $mualaf = Pelayanan::where('slug', 'surat-keterangan-mualaf')->first();
        $kiblat = Pelayanan::where('slug', 'kalibrasi-arah-kiblat')->first();

        // ==================== 1. PENDAFTARAN NIKAH ====================
        if ($nikah) {
            $fields = [
                // Kategori & Jadwal
                ['name' => 'kategori_pemohon', 'label' => 'Status Wilayah KTP Anda', 'type' => 'select', 'required' => true, 'options' => ['Warga Lokal', 'Warga Luar Daerah (Tumpangan)'], 'placeholder' => 'Pilih status wilayah', 'help_text' => null, 'order' => 1],
                ['name' => 'tanggal_akad', 'label' => 'Tanggal Akad Nikah', 'type' => 'date', 'required' => true, 'options' => null, 'placeholder' => null, 'help_text' => 'Minimal H-10 dari hari ini', 'order' => 2],
                ['name' => 'lokasi_akad', 'label' => 'Lokasi Pelaksanaan', 'type' => 'select', 'required' => true, 'options' => ['KUA', 'Luar KUA'], 'placeholder' => 'Pilih lokasi akad', 'help_text' => null, 'order' => 3],
                ['name' => 'alamat_akad', 'label' => 'Alamat Lengkap Lokasi Akad', 'type' => 'textarea', 'required' => true, 'options' => null, 'placeholder' => 'Masukkan alamat lengkap lokasi akad', 'help_text' => 'Jika lokasi di KUA, akan diisi otomatis alamat KUA', 'order' => 4],
                ['name' => 'mas_kawin', 'label' => 'Mas Kawin / Mahar', 'type' => 'textarea', 'required' => true, 'options' => null, 'placeholder' => 'Contoh: Emas 20 Gram dan Alat Shalat Dibayar Tunai', 'help_text' => null, 'order' => 5],

                // Data Calon Suami
                ['name' => 'pria_nik', 'label' => 'NIK Calon Suami', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => '16 digit NIK', 'help_text' => 'Harus 16 digit angka', 'order' => 6],
                ['name' => 'pria_nama', 'label' => 'Nama Lengkap Calon Suami', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Sesuai KTP', 'help_text' => 'Harus sesuai persis dengan KTP/Akta Lahir', 'order' => 7],
                ['name' => 'pria_tempat_lahir', 'label' => 'Tempat Lahir Pria', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Kota/Kabupaten', 'help_text' => null, 'order' => 8],
                ['name' => 'pria_tanggal_lahir', 'label' => 'Tanggal Lahir Pria', 'type' => 'date', 'required' => true, 'options' => null, 'placeholder' => null, 'help_text' => null, 'order' => 9],
                ['name' => 'pria_status', 'label' => 'Status Pernikahan Pria', 'type' => 'select', 'required' => true, 'options' => ['Perjaka', 'Duda'], 'placeholder' => 'Pilih status', 'help_text' => null, 'order' => 10],
                ['name' => 'pria_ayah_nama', 'label' => 'Nama Ayah Kandung Pria', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama ayah kandung', 'help_text' => 'Wajib dicatat di Buku Nikah', 'order' => 11],
                ['name' => 'pria_ibu_nama', 'label' => 'Nama Ibu Kandung Pria', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama ibu kandung', 'help_text' => 'Wajib dicatat di Buku Nikah', 'order' => 12],
                ['name' => 'pria_alamat_ktp', 'label' => 'Alamat KTP Pria', 'type' => 'textarea', 'required' => true, 'options' => null, 'placeholder' => 'Alamat sesuai KTP', 'help_text' => null, 'order' => 13],

                // Data Calon Istri
                ['name' => 'wanita_nik', 'label' => 'NIK Calon Istri', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => '16 digit NIK', 'help_text' => 'Harus 16 digit angka', 'order' => 14],
                ['name' => 'wanita_nama', 'label' => 'Nama Lengkap Calon Istri', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Sesuai KTP', 'help_text' => 'Harus sesuai persis dengan KTP/Akta Lahir', 'order' => 15],
                ['name' => 'wanita_tempat_lahir', 'label' => 'Tempat Lahir Wanita', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Kota/Kabupaten', 'help_text' => null, 'order' => 16],
                ['name' => 'wanita_tanggal_lahir', 'label' => 'Tanggal Lahir Wanita', 'type' => 'date', 'required' => true, 'options' => null, 'placeholder' => null, 'help_text' => null, 'order' => 17],
                ['name' => 'wanita_status', 'label' => 'Status Pernikahan Wanita', 'type' => 'select', 'required' => true, 'options' => ['Gadis', 'Janda'], 'placeholder' => 'Pilih status', 'help_text' => null, 'order' => 18],
                ['name' => 'wanita_ayah_nama', 'label' => 'Nama Ayah Kandung Wanita', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama ayah kandung', 'help_text' => 'Untuk penentuan dasar wali', 'order' => 19],
                ['name' => 'wanita_ibu_nama', 'label' => 'Nama Ibu Kandung Wanita', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama ibu kandung', 'help_text' => null, 'order' => 20],
                ['name' => 'wanita_alamat_ktp', 'label' => 'Alamat KTP Wanita', 'type' => 'textarea', 'required' => true, 'options' => null, 'placeholder' => 'Alamat sesuai KTP', 'help_text' => null, 'order' => 21],

                // Data Wali
                ['name' => 'wali_nama', 'label' => 'Nama Lengkap Wali', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama wali nikah', 'help_text' => null, 'order' => 22],
                ['name' => 'wali_hub', 'label' => 'Hubungan Wali', 'type' => 'select', 'required' => true, 'options' => ['Ayah Kandung', 'Saudara Laki', 'Paman', 'Wali Hakim'], 'placeholder' => 'Pilih hubungan wali', 'help_text' => null, 'order' => 23],

                // Kondisional (Warga Luar Daerah)
                ['name' => 'kua_asal_rekomendasi', 'label' => 'Nama KUA Asal', 'type' => 'text', 'required' => false, 'options' => null, 'placeholder' => 'Nama KUA asal', 'help_text' => 'Wajib jika Warga Luar Daerah', 'order' => 24],
                ['name' => 'no_surat_rekomendasi', 'label' => 'Nomor Surat Rekomendasi', 'type' => 'text', 'required' => false, 'options' => null, 'placeholder' => 'Nomor surat rekomendasi', 'help_text' => 'Wajib jika Warga Luar Daerah', 'order' => 25],

                // Upload Dokumen
                ['name' => 'file_pengantar_kelurahan', 'label' => 'Surat Pengantar Kelurahan (N1-N4)', 'type' => 'file', 'required' => true, 'options' => ['max_size' => 2048, 'mimes' => ['pdf']], 'placeholder' => null, 'help_text' => 'Format PDF, maks 2MB. Wajib untuk Warga Lokal', 'order' => 26],
                ['name' => 'file_surat_rekomendasi_kua', 'label' => 'Surat Rekomendasi KUA Asal', 'type' => 'file', 'required' => false, 'options' => ['max_size' => 2048, 'mimes' => ['pdf']], 'placeholder' => null, 'help_text' => 'Format PDF. Wajib jika Warga Luar Daerah', 'order' => 27],
                ['name' => 'file_ktp_dan_kk_mempelai', 'label' => 'KTP & KK Kedua Mempelai', 'type' => 'file', 'required' => true, 'options' => ['max_size' => 5120, 'mimes' => ['pdf'], 'multiple' => true], 'placeholder' => null, 'help_text' => 'Format PDF, masing-masing maks 5MB. Upload KTP & KK untuk kedua calon', 'order' => 28],
                ['name' => 'file_akta_kelahiran', 'label' => 'Akta Kelahiran', 'type' => 'file', 'required' => true, 'options' => ['max_size' => 2048, 'mimes' => ['pdf']], 'placeholder' => null, 'help_text' => 'Format PDF kedua mempelai, maks 2MB', 'order' => 29],
                ['name' => 'file_pasfoto_biru', 'label' => 'Pas Foto Latar Biru', 'type' => 'image', 'required' => true, 'options' => ['max_size' => 1024, 'mimes' => ['jpg', 'jpeg', 'png']], 'placeholder' => null, 'help_text' => 'Format JPG/PNG, maks 1MB', 'order' => 30],
                ['name' => 'file_akta_cerai', 'label' => 'Akta Cerai (Jika Duda/Janda Cerai)', 'type' => 'file', 'required' => false, 'options' => ['max_size' => 2048, 'mimes' => ['pdf']], 'placeholder' => null, 'help_text' => 'Wajib jika status Duda/Janda karena cerai hidup', 'order' => 31],
                ['name' => 'file_surat_kematian_pasangan', 'label' => 'Surat Kematian Pasangan (N6)', 'type' => 'file', 'required' => false, 'options' => ['max_size' => 2048, 'mimes' => ['pdf']], 'placeholder' => null, 'help_text' => 'Wajib jika Duda/Janda karena pasangan meninggal', 'order' => 32],
            ];

            foreach ($fields as $field) {
                FormField::firstOrCreate(
                    ['pelayanan_id' => $nikah->id, 'name' => $field['name']],
                    array_merge($field, ['active' => true]),
                );
            }
        }

        // ==================== 2. SERTIFIKASI WAKAF ====================
        if ($wakaf) {
            $fields = [
                ['name' => 'wakif_nik', 'label' => 'NIK Pemberi Wakaf (Wakif)', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => '16 digit NIK', 'help_text' => null, 'order' => 1],
                ['name' => 'wakif_nama', 'label' => 'Nama Lengkap Wakif', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Sesuai KTP', 'help_text' => null, 'order' => 2],
                ['name' => 'wakif_alamat', 'label' => 'Alamat KTP Wakif', 'type' => 'textarea', 'required' => true, 'options' => null, 'placeholder' => 'Alamat sesuai KTP', 'help_text' => null, 'order' => 3],
                ['name' => 'nazhir_nama_lembaga', 'label' => 'Nama Nazhir/Lembaga Pengelola', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama ketua/yayasan', 'help_text' => null, 'order' => 4],
                ['name' => 'jenis_aset', 'label' => 'Jenis Aset Wakaf', 'type' => 'select', 'required' => true, 'options' => ['Tanah', 'Bangunan'], 'placeholder' => 'Pilih jenis aset', 'help_text' => null, 'order' => 5],
                ['name' => 'luas_aset', 'label' => 'Luas Aset (m²)', 'type' => 'number', 'required' => true, 'options' => null, 'placeholder' => 'Luas dalam meter persegi', 'help_text' => null, 'order' => 6],
                ['name' => 'nomor_sertifikat', 'label' => 'Nomor Sertifikat Tanah', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nomor SHM/Letter C', 'help_text' => 'Nomor SHM / Letter C tanah', 'order' => 7],
                ['name' => 'peruntukan_wakaf', 'label' => 'Peruntukan Wakaf', 'type' => 'select', 'required' => true, 'options' => ['Masjid/Mushala', 'Makam', 'Sekolah', 'Fasilitas Umum'], 'placeholder' => 'Pilih peruntukan', 'help_text' => null, 'order' => 8],
                ['name' => 'saksi_1_nama', 'label' => 'Nama Saksi 1', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama saksi pertama', 'help_text' => null, 'order' => 9],
                ['name' => 'saksi_1_nik', 'label' => 'NIK Saksi 1', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => '16 digit NIK', 'help_text' => null, 'order' => 10],
                ['name' => 'saksi_2_nama', 'label' => 'Nama Saksi 2', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama saksi kedua', 'help_text' => null, 'order' => 11],
                ['name' => 'saksi_2_nik', 'label' => 'NIK Saksi 2', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => '16 digit NIK', 'help_text' => null, 'order' => 12],
                ['name' => 'file_ktp_wakif_dan_nazhir', 'label' => 'KTP Wakif & Nazhir', 'type' => 'file', 'required' => true, 'options' => ['max_size' => 5120, 'mimes' => ['pdf']], 'placeholder' => null, 'help_text' => 'Format PDF, maks 5MB', 'order' => 13],
                ['name' => 'file_sertifikat_tanah_asli', 'label' => 'Sertifikat Tanah Asli', 'type' => 'file', 'required' => true, 'options' => ['max_size' => 5120, 'mimes' => ['pdf']], 'placeholder' => null, 'help_text' => 'Format PDF, maks 5MB', 'order' => 14],
                ['name' => 'file_surat_pengantar_desa', 'label' => 'Surat Pengantar Desa', 'type' => 'file', 'required' => true, 'options' => ['max_size' => 2048, 'mimes' => ['pdf']], 'placeholder' => null, 'help_text' => 'Format PDF, maks 2MB', 'order' => 15],
            ];

            foreach ($fields as $field) {
                FormField::firstOrCreate(
                    ['pelayanan_id' => $wakaf->id, 'name' => $field['name']],
                    array_merge($field, ['active' => true]),
                );
            }
        }

        // ==================== 3. REKOMENDASI NIKAH KELUAR ====================
        if ($rekom) {
            $fields = [
                ['name' => 'pemohon_nik', 'label' => 'NIK Pemohon', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => '16 digit NIK', 'help_text' => null, 'order' => 1],
                ['name' => 'pemohon_nama', 'label' => 'Nama Lengkap Pemohon', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Sesuai KTP', 'help_text' => null, 'order' => 2],
                ['name' => 'pemohon_alamat', 'label' => 'Alamat Domisili Asal', 'type' => 'textarea', 'required' => true, 'options' => null, 'placeholder' => 'Alamat sesuai KTP', 'help_text' => null, 'order' => 3],
                ['name' => 'pria_atau_wanita', 'label' => 'Status Pemohon', 'type' => 'select', 'required' => true, 'options' => ['Saya Pria (Pindah Kawin)', 'Saya Wanita (Pindah Kawin)'], 'placeholder' => 'Pilih status', 'help_text' => null, 'order' => 4],
                ['name' => 'kua_kecamatan_tujuan', 'label' => 'KUA Kecamatan Tujuan', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama KUA tujuan', 'help_text' => null, 'order' => 5],
                ['name' => 'kabupaten_kota_tujuan', 'label' => 'Kabupaten/Kota Tujuan', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama kabupaten/kota', 'help_text' => null, 'order' => 6],
                ['name' => 'provinsi_tujuan', 'label' => 'Provinsi Tujuan', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama provinsi', 'help_text' => null, 'order' => 7],
                ['name' => 'file_ktp_dan_kk', 'label' => 'KTP & KK Pemohon', 'type' => 'file', 'required' => true, 'options' => ['max_size' => 5120, 'mimes' => ['pdf']], 'placeholder' => null, 'help_text' => 'Format PDF, maks 5MB', 'order' => 8],
                ['name' => 'file_pengantar_kelurahan_lokal', 'label' => 'Surat Pengantar Kelurahan (N1-N4)', 'type' => 'file', 'required' => true, 'options' => ['max_size' => 2048, 'mimes' => ['pdf']], 'placeholder' => null, 'help_text' => 'Format PDF dari kelurahan asal, maks 2MB', 'order' => 9],
            ];

            foreach ($fields as $field) {
                FormField::firstOrCreate(
                    ['pelayanan_id' => $rekom->id, 'name' => $field['name']],
                    array_merge($field, ['active' => true]),
                );
            }
        }

        // ==================== 4. SURAT KETERANGAN MUALAF ====================
        if ($mualaf) {
            $fields = [
                ['name' => 'pemohon_nik', 'label' => 'NIK Pemohon', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => '16 digit NIK', 'help_text' => null, 'order' => 1],
                ['name' => 'nama_lama', 'label' => 'Nama Lengkap (Sebelum Islam)', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama di KTP asal', 'help_text' => null, 'order' => 2],
                ['name' => 'nama_baru', 'label' => 'Nama Islami Baru (Opsional)', 'type' => 'text', 'required' => false, 'options' => null, 'placeholder' => 'Nama pilihan setelah masuk Islam', 'help_text' => 'Opsional, boleh dikosongkan', 'order' => 3],
                ['name' => 'tempat_tanggal_lahir', 'label' => 'Tempat & Tanggal Lahir', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Tempat, Tanggal Lahir', 'help_text' => null, 'order' => 4],
                ['name' => 'tanggal_syahadat', 'label' => 'Tanggal Syahadat', 'type' => 'date', 'required' => true, 'options' => null, 'placeholder' => null, 'help_text' => 'Tanggal pengucapan kalimat syahadat', 'order' => 5],
                ['name' => 'tempat_syahadat', 'label' => 'Tempat Syahadat', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama masjid/lembaga', 'help_text' => 'Nama masjid atau lembaga tempat ikrar syahadat', 'order' => 6],
                ['name' => 'nama_pembimbing', 'label' => 'Nama Pembimbing Syahadat', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama Ustadz/Kyai', 'help_text' => null, 'order' => 7],
                ['name' => 'file_ktp_asli', 'label' => 'Foto KTP Asli', 'type' => 'image', 'required' => true, 'options' => ['max_size' => 2048, 'mimes' => ['jpg', 'jpeg', 'png']], 'placeholder' => null, 'help_text' => 'Format JPG/PNG, maks 2MB', 'order' => 8],
                ['name' => 'file_surat_pernyataan_mualaf', 'label' => 'Surat Pernyataan Mualaf', 'type' => 'file', 'required' => true, 'options' => ['max_size' => 2048, 'mimes' => ['pdf']], 'placeholder' => null, 'help_text' => 'Surat pernyataan masuk Islam sukarela bermeterai, PDF maks 2MB', 'order' => 9],
                ['name' => 'file_dokumentasi_syahadat', 'label' => 'Dokumentasi Syahadat', 'type' => 'image', 'required' => true, 'options' => ['max_size' => 5120, 'mimes' => ['jpg', 'jpeg', 'png'], 'multiple' => true], 'placeholder' => null, 'help_text' => 'Bukti foto prosesi syahadat bersama pembimbing & saksi, masing-masing maks 5MB', 'order' => 10],
            ];

            foreach ($fields as $field) {
                FormField::firstOrCreate(
                    ['pelayanan_id' => $mualaf->id, 'name' => $field['name']],
                    array_merge($field, ['active' => true]),
                );
            }
        }

        // ==================== 5. KALIBRASI ARAH KIBLAT ====================
        if ($kiblat) {
            $fields = [
                ['name' => 'nama_tempat_ibadah', 'label' => 'Nama Tempat Ibadah', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Contoh: Masjid Baitul Muttaqin', 'help_text' => null, 'order' => 1],
                ['name' => 'alamat_lengkap', 'label' => 'Alamat Lengkap', 'type' => 'textarea', 'required' => true, 'options' => null, 'placeholder' => 'Lokasi fisik tempat ibadah', 'help_text' => null, 'order' => 2],
                ['name' => 'koordinat_gps', 'label' => 'Koordinat GPS', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Contoh: -6.123456, 106.123456', 'help_text' => 'Salin titik koordinat dari Google Maps (Latitude, Longitude)', 'order' => 3],
                ['name' => 'nama_kontak_pencari', 'label' => 'Nama Kontak Pengurus', 'type' => 'text', 'required' => true, 'options' => null, 'placeholder' => 'Nama perwakilan takmir', 'help_text' => null, 'order' => 4],
                ['name' => 'no_hp_kontak', 'label' => 'No. HP Kontak', 'type' => 'tel', 'required' => true, 'options' => null, 'placeholder' => '08xxxxxxxxxx', 'help_text' => 'Nomor WhatsApp aktif untuk koordinasi lapangan', 'order' => 5],
                ['name' => 'file_surat_permohonan_takmir', 'label' => 'Surat Permohonan Takmir', 'type' => 'file', 'required' => true, 'options' => ['max_size' => 2048, 'mimes' => ['pdf']], 'placeholder' => null, 'help_text' => 'Surat resmi dari pengurus/takmir lengkap stempel, PDF maks 2MB', 'order' => 6],
            ];

            foreach ($fields as $field) {
                FormField::firstOrCreate(
                    ['pelayanan_id' => $kiblat->id, 'name' => $field['name']],
                    array_merge($field, ['active' => true]),
                );
            }
        }
    }
}
