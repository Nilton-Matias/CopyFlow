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

    public function promptInput()
    {
        return $this->belongsTo(PromptInput::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
