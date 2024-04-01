<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recruiter extends Model
{
    use HasFactory;

    public function category() :BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function job_post(): HasMany
    {
        return $this->hasMany(Apply::class);
    }
    public function jobpost():HasMany
    {
        return $this->hasMany(JobPost::class);
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
