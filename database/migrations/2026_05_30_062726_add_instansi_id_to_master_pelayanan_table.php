<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master.pelayanan', function (Blueprint $table) {
            $table->foreignId('instansi_id')->nullable()->constrained('master.instansi')->nullOnDelete()->after('nama_pelayanan');
        });
    }

    public function down(): void
    {
        Schema::table('master.pelayanan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('instansi_id');
        });
    }
};
