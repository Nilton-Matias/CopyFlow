<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedText extends Model
{
    protected $fillable = [
        'user_id',
        'prompt_input_id',
        'prompt',
        'generated_text',
    ];

    protected $with = ['user']; // Isso já traz os dados do user junto com GeneratedText


    public function promptInput()
    {
        return $this->belongsTo(PromptInput::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function historyGeneratedTexts()
    {
        return $this->hasMany(HistoryGenerated::class);
    }
}
