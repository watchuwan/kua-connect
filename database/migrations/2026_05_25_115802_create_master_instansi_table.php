<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master.instansi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_instansi')->unique();
            $table->string('nama_instansi');
            $table->text('deskripsi_layanan')->nullable();
            $table->string('tipe');
            $table->string('slug')->unique();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master.instansi');
    }
};
