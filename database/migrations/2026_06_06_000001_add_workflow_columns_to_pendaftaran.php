<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelayanan.pendaftaran', function (Blueprint $table) {
            $table->text('catatan')->nullable()->after('status');
            $table->string('no_surat')->nullable()->after('catatan');
            $table->timestamp('jadwal_kedatangan')->nullable()->after('no_surat');
            $table->string('derajat_kiblat')->nullable()->after('jadwal_kedatangan');
        });
    }

    public function down(): void
    {
        Schema::table('pelayanan.pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['catatan', 'no_surat', 'jadwal_kedatangan', 'derajat_kiblat']);
        });
    }
};
