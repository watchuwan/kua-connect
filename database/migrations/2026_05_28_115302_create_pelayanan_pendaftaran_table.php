<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelayanan.pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_antrean')->unique();
            $table->foreignId('pelayanan_id')->constrained('master.pelayanan')->restrictOnDelete();
            $table->jsonb('data');
            $table->string('status')->default('waiting');
            $table->string('slug')->unique();
            $table->timestamp('waktu_dilayani')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelayanan.pendaftaran');
    }
};
