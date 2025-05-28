<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('history_generated_texts', function (Blueprint $table) {
            $table->unique(['user_id', 'generated_text_id'], 'unique_user_generated_text');
        });
    }

    public function down(): void
    {
        Schema::table('history_generated_texts', function (Blueprint $table) {
            $table->dropUnique('unique_user_generated_text');
        });
    }
};
