<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("master.form_fields", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("pelayanan_id")
                ->constrained("master.pelayanan")
                ->cascadeOnDelete();
            $table->string("name"); // nama field (key)
            $table->string("label"); // label tampilan
            $table->string("type")->default("text"); // text, email, tel, number, textarea, select, date, etc
            $table->boolean("required")->default(false);
            $table->text("options")->nullable(); // untuk select/radio/checkbox (JSON array)
            $table->text("help_text")->nullable();
            $table->integer("order")->default(0); // urutan field
            $table->jsonb("validation_rules")->nullable(); // rules tambahan
            $table->boolean("active")->default(true);
            $table->timestamps();

            // Index untuk performa
            $table->index(["pelayanan_id", "order"]);
            $table->unique(["pelayanan_id", "name"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("master.form_fields");
    }
};
