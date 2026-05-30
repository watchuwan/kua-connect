<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master.pelayanan', function (Blueprint $table) {
            $table->dropColumn('form_schema');
        });
    }

    public function down(): void
    {
        Schema::table('master.pelayanan', function (Blueprint $table) {
            $table->jsonb('form_schema')->nullable();
        });
    }
};
