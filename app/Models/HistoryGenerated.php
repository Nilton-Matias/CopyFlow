<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryGenerated extends Model
{
    protected $table = 'history_generated_texts';

    protected $fillable = [
        'user_id',
        'generated_text_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generated_text()
    {
        return $this->belongsTo(GeneratedText::class);
    }
}
