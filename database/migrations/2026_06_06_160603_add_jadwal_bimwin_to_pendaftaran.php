<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pelayanan.pendaftaran', function (Blueprint $table) {
            $table->timestamp('jadwal_bimwin')->nullable()->after('jadwal_kedatangan');
        });
    }

    public function down(): void
    {
        Schema::table('pelayanan.pendaftaran', function (Blueprint $table) {
            $table->dropColumn('jadwal_bimwin');
        });
    }
};
