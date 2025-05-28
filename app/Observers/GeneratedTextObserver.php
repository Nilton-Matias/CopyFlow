<?php

namespace App\Observers;

use App\Models\GeneratedText;
use App\Models\HistoryGenerated;

class GeneratedTextObserver
{
    /**
     * Handle the GeneratedText "created" event.
     */
    public function created(GeneratedText $generatedText): void
    {
        // Verifica manualmente se já existe antes de criar
        $exists = HistoryGenerated::where('user_id', $generatedText->user_id)
            ->where('generated_text_id', $generatedText->id)
            ->exists();

        if (!$exists) {
            HistoryGenerated::create([
                'user_id' => $generatedText->user_id,
                'generated_text_id' => $generatedText->id,
            ]);
        }
    }


    /**
     * Handle the GeneratedText "updated" event.
     */
    public function updated(GeneratedText $generatedText): void
    {
        //
    }

    /**
     * Handle the GeneratedText "deleted" event.
     */
    public function deleted(GeneratedText $generatedText): void
    {
        //
    }

    /**
     * Handle the GeneratedText "restored" event.
     */
    public function restored(GeneratedText $generatedText): void
    {
        //
    }

    /**
     * Handle the GeneratedText "force deleted" event.
     */
    public function forceDeleted(GeneratedText $generatedText): void
    {
        //
    }
}
