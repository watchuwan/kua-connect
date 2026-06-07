Berikut adalah blueprint arsitektur sistem **KUA-Connect** berskala lokal (Single District) yang **Full Online**, mandiri (tanpa API Dukcapil/SatuSehat), dan telah disempurnakan dengan seluruh elemen regulasi riil Kemenag RI.

Struktur ini dirancang agar Anda bisa langsung melakukan *seeding* ke tabel `master.form_fields` dan mengatur logic *state/workflow* pada aplikasi Laravel & Filament Anda.

---

## 1. LAYANAN PENDAFTARAN NIKAH (Skenario Mandiri & Kondisional)

Formulir ini dirancang menggunakan *Wizard/Multi-step* di frontend. Sistem akan meminta dokumen tambahan secara otomatis berdasarkan status perkawinan dan domisili KTP.

### A. List Field Form (`master.form_fields`)

| order | name | label | type | required | conditional_rule / Keterangan |
| --- | --- | --- | --- | --- | --- |
| **1** | `kategori_pemohon` | Status Wilayah KTP Anda | `select` | true | Options: `["Warga Lokal", "Warga Luar Daerah (Tumpangan)"]` |
| **2** | `tanggal_akad` | Tanggal Akad Nikah | `date` | true | Sistem mengunci otomatis: Minimal H-10 dari hari ini. |
| **3** | `lokasi_akad` | Lokasi Pelaksanaan | `select` | true | Options: `["KUA", "Luar KUA"]` |
| **4** | `alamat_akad` | Alamat Lengkap Lokasi Akad | `textarea` | true | Jika `lokasi_akad` = "KUA", auto-fill alamat kantor KUA. |
| **5** | `mas_kawin` | Mas Kawin / Mahar | `textarea` | true | Contoh: "Emas 20 Gram dan Alat Shalat Dibayar Tunai". |
| **6** | `pria_nik` | NIK Calon Suami | `text` | true | Validasi: Harus 16 digit angka. |
| **7** | `pria_nama` | Nama Lengkap Calon Suami | `text` | true | Harus sesuai persis dengan KTP/Akta Lahir. |
| **8** | `pria_tempat_lahir` | Tempat Lahir Pria | `text` | true | *null* |
| **9** | `pria_tanggal_lahir` | Tanggal Lahir Pria | `date` | true | Sistem menghitung usia otomatis. |
| **10** | `pria_status` | Status Pernikahan Pria | `select` | true | Options: `["Perjaka", "Duda"]` |
| **11** | `pria_ayah_nama` | Nama Ayah Kandung Pria | `text` | true | Wajib dicatat di Buku Nikah fisik. |
| **12** | `pria_ibu_nama` | Nama Ibu Kandung Pria | `text` | true | Wajib dicatat di Buku Nikah fisik. |
| **13** | `pria_alamat_ktp` | Alamat KTP Pria | `textarea` | true | *null* |
| **14** | `wanita_nik` | NIK Calon Istri | `text` | true | Validasi: Harus 16 digit angka. |
| **15** | `wanita_nama` | Nama Lengkap Calon Istri | `text` | true | Harus sesuai persis dengan KTP/Akta Lahir. |
| **16** | `wanita_tempat_lahir` | Tempat Lahir Wanita | `text` | true | *null* |
| **17** | `wanita_tanggal_lahir` | Tanggal Lahir Wanita | `date` | true | *null* |
| **18** | `wanita_status` | Status Pernikahan Wanita | `select` | true | Options: `["Gadis", "Janda"]` |
| **19** | `wanita_ayah_nama` | Nama Ayah Kandung Wanita | `text` | true | Wajib untuk penentuan dasar wali. |
| **20** | `wanita_ibu_nama` | Nama Ibu Kandung Wanita | `text` | true | *null* |
| **21** | `wanita_alamat_ktp` | Alamat KTP Wanita | `textarea` | true | *null* |
| **22** | `wali_nama` | Nama Lengkap Wali | `text` | true | *null* |
| **23** | `wali_hub` | Hubungan Wali | `select` | true | Options: `["Ayah Kandung", "Saudara Laki", "Paman", "Wali Hakim"]` |
| **24** | `kua_asal_rekomendasi` | Nama KUA Asal | `text` | **true** | **Hanya wajib diisi jika `kategori_pemohon` = "Warga Luar Daerah"** |
| **25** | `no_surat_rekomendasi` | Nomor Surat Rekomendasi | `text` | **true** | **Hanya wajib diisi jika `kategori_pemohon` = "Warga Luar Daerah"** |

### B. List File Upload (Dokumen Pendukung)

* `file_pengantar_kelurahan` (PDF Form N1-N4, Wajib untuk Warga Lokal).
* `file_surat_rekomendasi_kua` (PDF, Wajib jika `kategori_pemohon` = "Warga Luar Daerah").
* `file_ktp_dan_kk_mempelai` (PDF gabungan KTP & KK kedua mempelai).
* `file_akta_kelahiran` (PDF kedua mempelai).
* `file_pasfoto_biru` (JPG).
* `file_akta_cerai` (PDF, **Kondisional:** Wajib di-upload jika `pria_status` = "Duda" ATAU `wanita_status` = "Janda" karena cerai hidup).
* `file_surat_kematian_pasangan` (PDF Form N6, **Kondisional:** Wajib di-upload jika status "Duda/Janda" karena pasangan lama meninggal dunia).

### C. Langkah Pendaftaran Online KUA-Connect

1. **Penginputan Mandiri:** Warga mengisi data identitas diri, orang tua, mas kawin, dan mengunggah seluruh dokumen pendukung (termasuk berkas kondisional duda/janda jika ada) secara online.
2. **Verifikasi Berkas Digital (Status: `Pending`):** Staf KUA memeriksa kecocokan antara data teks yang diketik warga dengan berkas *scan* asli yang diunggah. Jika ada ketidakcocokan, staf mengubah status menjadi `Perlu Revisi` dan memberikan catatan error.
3. **Pembayaran Non-Tunai:** Jika memilih lokasi akad di "Luar KUA", sistem menampilkan halaman petunjuk pembayaran PNBP sebesar Rp 600.000 ke rekening resmi KUA daerah tersebut (bisa via QRIS manual/transfer bank). Warga wajib mengunggah bukti transfernya ke sistem.
4. **Pemeriksaan Fisik & Bimwin (Status: `Verifikasi Fisik`):** Pengguna dijadwalkan datang ke kantor KUA sekali sebelum hari H untuk memperlihatkan dokumen asli (mencegah pemalsuan berkas karena tidak adanya API Dukcapil) sekaligus mengikuti Kursus Calon Pengantin (Bimwin).
5. **Kunci Jadwal Penghulu (Status: `Jadwal Dikunci`):** Admin KUA memploting penghulu yang bertugas. Pada hari H, penghulu datang membawa Buku Nikah fisik yang datanya sudah valid sesuai aplikasi. Setelah akad selesai, status diubah menjadi `Selesai`.

---

## 2. LAYANAN CERTIFICATION WAKAF (E-Wakaf Lokal)

Layanan pengajuan legalitas aset keagamaan untuk diproses menjadi Akta Ikrar Wakaf resmi.

### A. List Field Form (`master.form_fields`)

* `wakif_nik` (`text`, required) -> NIK pemberi wakaf.
* `wakif_nama` (`text`, required) -> Nama lengkap sesuai KTP.
* `wakif_alamat` (`textarea`, required) -> Alamat KTP Wakif.
* `nazhir_nama_lembaga` (`text`, required) -> Nama ketua/yayasan pengelola wakaf.
* `jenis_aset` (`select`, required) -> Options: `["Tanah", "Bangunan"]`.
* `luas_aset` (`number`, required) -> Luas dalam $m^2$.
* `nomor_sertifikat` (`text`, required) -> Nomor SHM / Letter C tanah.
* `peruntukan_wakaf` (`select`, required) -> Options: `["Masjid/Mushala", "Makam", "Sekolah", "Fasilitas Umum"]`.
* `saksi_1_nama` (`text`, required) -> Nama saksi pertama ikrar wakaf.
* `saksi_1_nik` (`text`, required) -> NIK saksi pertama.
* `saksi_2_nama` (`text`, required) -> Nama saksi kedua.
* `saksi_2_nik` (`text`, required) -> NIK saksi kedua.

### B. List File Upload

* `file_ktp_wakif_dan_nazhir` (PDF).
* `file_sertifikat_tanah_asli` (PDF) -> Untuk validasi kesesuaian luas dan nomor sertifikat secara manual oleh staf.
* `file_surat_pengantar_desa` (PDF).

### C. Langkah Pendaftaran Online

1. Warga menginput seluruh spesifikasi aset, data saksi, dan mengunggah sertifikat kepemilikan tanah.
2. Staf KUA mencocokkan validitas berkas pertanahan secara visual di dashboard admin.
3. Jika sesuai, sistem mengubah status menjadi `Siap Ikrar` dan menerbitkan kalender pilihan jadwal kedatangan.
4. Wakif, Nazhir, dan 2 Saksi datang ke KUA sesuai jadwal untuk mengucapkan ikrar wakaf lisan secara sah di depan Kepala KUA.
5. Admin menginput nomor Akta Ikrar Wakaf (AIW) yang telah terbit secara fisik, mengunggah hasilnya ke sistem, dan mengubah status menjadi `Selesai`.

---

## 3. LAYANAN REKOMENDASI NIKAH (PINDAH NIKAH KELUAR)

Layanan 100% online untuk warga lokal daerah Anda yang ingin menumpang nikah di kecamatan lain.

### A. List Field Form (`master.form_fields`)

* `pemohon_nik` (`text`, required) -> NIK pemohon.
* `pemohon_nama` (`text`, required) -> Nama lengkap pemohon sesuai KTP.
* `pemohon_alamat` (`textarea`, required) -> Alamat domisili asal.
* `pria_atau_wanita` (`select`, required) -> Options: `["Saya Pria (Pindah Kawin)", "Saya Wanita (Pindah Kawin)"]`.
* `kua_kecamatan_tujuan` (`text`, required) -> KUA Kecamatan tempat akad dilaksanakan.
* `kabupaten_kota_tujuan` (`text`, required) -> Kabupaten/Kota tujuan.
* `provinsi_tujuan` (`text`, required) -> Provinsi tujuan.

### B. List File Upload

* `file_ktp_dan_kk` (PDF) -> Untuk validasi manual oleh staf bahwa pemohon benar-benar warga lokal di bawah yurisdiksi KUA tersebut.
* `file_pengantar_kelurahan_lokal` (PDF Form N1-N4 dari kelurahan asal).

### C. Langkah Pendaftaran Full Online

1. Warga mengisi data lokasi KUA tujuan dan mengunggah berkas pengantar dari kelurahan asal.
2. Staf KUA daerah Anda memeriksa kecocokan data domisili pada KTP pemohon.
3. Kepala KUA menyetujui secara digital, dan sistem otomatis men-generate **Surat Rekomendasi Nikah resmi ber-QR Code/Tanda Tangan Elektronik (PDF)**.
4. Status berubah menjadi `Selesai`, warga mendapatkan notifikasi, dan dapat langsung mendownload file PDF dari aplikasi tanpa perlu melangkah ke kantor KUA asal.

---

## 4. LAYANAN SURAT KETERANGAN MUALAF

Penerbitan surat legalitas keagamaan pasca-warga melakukan prosesi masuk Islam.

### A. List Field Form (`master.form_fields`)

* `pemohon_nik` (`text`, required) -> NIK pemohon.
* `nama_lama` (`text`, required) -> Nama lengkap pemohon di KTP asal.
* `nama_baru` (`text`, false) -> Nama pilihan Islami yang baru (opsional).
* `tempat_tanggal_lahir` (`text`, required) -> Tempat dan tanggal lahir pemohon.
* `tanggal_syahadat` (`date`, required) -> Tanggal pengucapan kalimat syahadat.
* `tempat_syahadat` (`text`, required) -> Nama masjid atau lembaga tempat ikrar syahadat dilaksanakan.
* `nama_pembimbing` (`text`, required) -> Nama Ustadz/Kyai yang menuntun syahadat.

### B. List File Upload

* `file_ktp_asli` (JPG/PDF).
* `file_surat_pernyataan_mualaf` (PDF Surat pernyataan masuk Islam sukarela bermeterai).
* `file_dokumentasi_syahadat` (JPG Bukti foto prosesi syahadat bersama pembimbing & saksi).

### C. Langkah Pendaftaran Full Online

1. Pemohon (atau dibantu pengurus masjid) menginput data riwayat pensyahadatan manual dan mengunggah bukti-bukti fisik.
2. Admin KUA melakukan verifikasi manual terhadap nama pemohon, keaslian surat pernyataan, dan bukti foto dokumentasi.
3. Kepala KUA memberikan persetujuan digital, dan **Surat Keterangan Mualaf Resmi ber-QR Code** terbit di sistem.
4. Mualaf mengunduh file PDF tersebut untuk digunakan sebagai dasar hukum ke Dinas Dukcapil guna merubah kolom agama pada KTP dan KK fisik mereka.

---

## 5. LAYANAN KALIBRASI ARAH KIBLAT

Layanan request pengukuran presisi arah kiblat untuk masjid, mushala, atau tanah pekarangan.

### A. List Field Form (`master.form_fields`)

* `nama_tempat_ibadah` (`text`, required) -> Contoh: "Masjid Baitul Muttaqin".
* `alamat_lengkap` (`textarea`, required) -> Lokasi fisik tempat ibadah.
* `koordinat_gps` (`text`, required) -> Titik koordinat Latitude & Longitude (Disalin manual oleh warga dari pin titik lokasi Google Maps).
* `nama_kontak_pencari` (`text`, required) -> Nama perwakilan takmir/pengurus masjid.
* `no_hp_kontak` (`text`, required) -> Nomor WhatsApp aktif pengurus untuk koordinasi lapangan.

### B. List File Upload

* `file_surat_permohonan_takmir` (PDF Surat resmi permohonan dari pengurus/takmir lengkap dengan stempel masjid).

### C. Langkah Pendaftaran Online

1. Pengurus masjid menginput alamat, nomor HP kontak, dan menyalin titik koordinat maps secara manual di form KUA-Connect.
2. Staf KUA memverifikasi validitas surat permohonan takmir dan mengecek titik koordinat GPS yang dimasukkan warga melalui peta digital pada dashboard admin.
3. Admin mengatur jadwal kunjungan Tim Hisab Rukyat KUA lapangan, lalu sistem mengirimkan notifikasi jadwal otomatis ke WhatsApp pengurus.
4. Petugas datang ke lapangan melakukan kalibrasi menggunakan kompas/theodolite.
5. Sepulangnya dari lapangan, petugas memasukkan data derajat kemiringan kiblat yang sah ke sistem, lalu mengunggah **Sertifikat Akurasi Arah Kiblat Digital** ke akun warga. Status berubah menjadi `Selesai`.
