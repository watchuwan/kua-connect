<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master.pelayanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelayanan')->unique();
            $table->jsonb('form_schema')->nullable();
            $table->string('slug')->unique();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master.pelayanan');
    }
};
