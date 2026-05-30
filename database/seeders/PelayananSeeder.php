<?php

namespace Database\Seeders;

use App\Models\Instansi;
use App\Models\Pelayanan;
use Illuminate\Database\Seeder;

class PelayananSeeder extends Seeder
{
    public function run(): void
    {
        $disdukcapil = Instansi::where('kode_instansi', 'DISDUKCAPIL')->first();
        $dpmptsp = Instansi::where('kode_instansi', 'DPMPTSP')->first();
        $dinkes = Instansi::where('kode_instansi', 'DINKES')->first();
        $dinsos = Instansi::where('kode_instansi', 'DINSOS')->first();
        $disdik = Instansi::where('kode_instansi', 'DISDIK')->first();
        $kecTigaraksa = Instansi::where('kode_instansi', 'KEC-TIGARAKSA')->first();
        $kecBalaraja = Instansi::where('kode_instansi', 'KEC-BALARAJA')->first();
        $kecCikupa = Instansi::where('kode_instansi', 'KEC-CIKUPA')->first();

        $pelayanans = [];

        if ($disdukcapil) {
            $pelayanans = array_merge($pelayanans, [
                ['nama_pelayanan' => 'Pembuatan KTP Elektronik', 'instansi_id' => $disdukcapil->id],
                ['nama_pelayanan' => 'Pembuatan Kartu Keluarga', 'instansi_id' => $disdukcapil->id],
                ['nama_pelayanan' => 'Pembuatan Akta Kelahiran', 'instansi_id' => $disdukcapil->id],
                ['nama_pelayanan' => 'Pembuatan Akta Kematian', 'instansi_id' => $disdukcapil->id],
                ['nama_pelayanan' => 'Surat Keterangan Pindah Datang', 'instansi_id' => $disdukcapil->id],
            ]);
        }

        if ($dpmptsp) {
            $pelayanans = array_merge($pelayanans, [
                ['nama_pelayanan' => 'Izin Mendirikan Bangunan', 'instansi_id' => $dpmptsp->id],
                ['nama_pelayanan' => 'Izin Usaha Mikro Kecil', 'instansi_id' => $dpmptsp->id],
                ['nama_pelayanan' => 'Surat Izin Tempat Usaha', 'instansi_id' => $dpmptsp->id],
            ]);
        }

        if ($dinkes) {
            $pelayanans = array_merge($pelayanans, [
                ['nama_pelayanan' => 'Izin Operasional Fasyankes', 'instansi_id' => $dinkes->id],
                ['nama_pelayanan' => 'Surat Keterangan Sehat', 'instansi_id' => $dinkes->id],
            ]);
        }

        if ($dinsos) {
            $pelayanans = array_merge($pelayanans, [
                ['nama_pelayanan' => 'Pendaftaran Bantuan Sosial', 'instansi_id' => $dinsos->id],
                ['nama_pelayanan' => 'Surat Keterangan Tidak Mampu', 'instansi_id' => $dinsos->id],
            ]);
        }

        if ($disdik) {
            $pelayanans = array_merge($pelayanans, [
                ['nama_pelayanan' => 'Izin Operasional Satuan Pendidikan', 'instansi_id' => $disdik->id],
            ]);
        }

        if ($kecTigaraksa) {
            $pelayanans = array_merge($pelayanans, [
                ['nama_pelayanan' => 'Surat Keterangan Domisili Tigaraksa', 'instansi_id' => $kecTigaraksa->id],
                ['nama_pelayanan' => 'Surat Keterangan Usaha Tigaraksa', 'instansi_id' => $kecTigaraksa->id],
                ['nama_pelayanan' => 'Pengesahan Surat Pindah Tigaraksa', 'instansi_id' => $kecTigaraksa->id],
            ]);
        }

        if ($kecBalaraja) {
            $pelayanans = array_merge($pelayanans, [
                ['nama_pelayanan' => 'Surat Keterangan Domisili Balaraja', 'instansi_id' => $kecBalaraja->id],
                ['nama_pelayanan' => 'Surat Keterangan Usaha Balaraja', 'instansi_id' => $kecBalaraja->id],
                ['nama_pelayanan' => 'Pengesahan Surat Pindah Balaraja', 'instansi_id' => $kecBalaraja->id],
            ]);
        }

        if ($kecCikupa) {
            $pelayanans = array_merge($pelayanans, [
                ['nama_pelayanan' => 'Surat Keterangan Domisili Cikupa', 'instansi_id' => $kecCikupa->id],
                ['nama_pelayanan' => 'Surat Keterangan Usaha Cikupa', 'instansi_id' => $kecCikupa->id],
                ['nama_pelayanan' => 'Pengesahan Surat Pindah Cikupa', 'instansi_id' => $kecCikupa->id],
            ]);
        }

        foreach ($pelayanans as $pelayanan) {
            Pelayanan::firstOrCreate(
                ['nama_pelayanan' => $pelayanan['nama_pelayanan']],
                array_merge($pelayanan, ['aktif' => true]),
            );
        }
    }
}
