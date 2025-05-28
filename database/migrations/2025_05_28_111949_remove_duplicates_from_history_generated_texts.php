<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Manter apenas o menor ID para cada par (user_id, generated_text_id)
        $duplicates = DB::table('history_generated_texts')
            ->select('user_id', 'generated_text_id', DB::raw('MIN(id) as id'))
            ->groupBy('user_id', 'generated_text_id')
            ->pluck('id')
            ->toArray();

        DB::table('history_generated_texts')
            ->whereNotIn('id', $duplicates)
            ->delete();
    }

    public function down(): void
    {
        // Nada a fazer no down
    }
};
