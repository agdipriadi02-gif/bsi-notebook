<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $fillable = [
        'user_id', 'title', 'file_path', 'file_type',
        'file_size', 'status', 'summary', 'content_text', 'cover_color'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'processing' => 'Memproses',
            'indexed' => 'Terindeks',
            'error' => 'Error',
            default => 'Unknown',
        };
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) return '';
        $bytes = (int) $this->file_size;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1024 * 1024) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / (1024 * 1024), 1) . ' MB';
    }
}
