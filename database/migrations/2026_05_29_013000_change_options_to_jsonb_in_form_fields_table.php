<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE master.form_fields ALTER COLUMN options TYPE jsonb USING CASE WHEN options IS NULL THEN NULL ELSE options::jsonb END");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE master.form_fields ALTER COLUMN options TYPE text USING CASE WHEN options IS NULL THEN NULL ELSE options::text END");
    }
};
