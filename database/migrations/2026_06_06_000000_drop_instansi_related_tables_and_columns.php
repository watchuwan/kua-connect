<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('master.instansi');

        if (Schema::hasColumn('master.pelayanan', 'instansi_id')) {
            Schema::table('master.pelayanan', function (Blueprint $table) {
                $table->dropConstrainedForeignId('instansi_id');
            });
        }

        if (Schema::hasColumn('users', 'instansi_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('instansi_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('instansi_id')->nullable()->constrained('master.instansi')->nullOnDelete();
        });

        Schema::table('master.pelayanan', function (Blueprint $table) {
            $table->foreignId('instansi_id')->nullable()->constrained('master.instansi')->nullOnDelete();
        });

        Schema::create('master.instansi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_instansi')->unique();
            $table->string('nama_instansi');
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('website')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }
};
