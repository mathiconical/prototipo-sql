<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question_id',
        'valor',
        'resposta',
        'error_msg',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
