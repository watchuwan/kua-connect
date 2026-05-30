<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master.instansi', function (Blueprint $table) {
            $table->string('kecamatan')->nullable()->after('deskripsi_layanan');
        });
    }

    public function down(): void
    {
        Schema::table('master.instansi', function (Blueprint $table) {
            $table->dropColumn('kecamatan');
        });
    }
};
