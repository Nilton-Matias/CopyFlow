<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromptInput extends Model
{
    protected $fillable = [
        'user_id',
        'product_name',
        'benefits',
        'audience',
        'goal',
        'platform',
        'tone',
        'language'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generatedTexts()
    {
        return $this->hasMany(GeneratedText::class);
    }
}
