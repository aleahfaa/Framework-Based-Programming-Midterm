<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'category_id', 'name', 'details', 'subtasks', 'deadline', 'is_completed'
    ];
    protected $casts = [
        'subtasks' => 'array', 'deadline' => 'date', 'is_completed' => 'boolean',
    ];
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }
}
