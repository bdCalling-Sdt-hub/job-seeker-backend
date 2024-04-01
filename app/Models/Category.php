<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function jobpost():HasMany
    {
        return $this->hasMany(JobPost::class);
    }

    public function recruiter():HasMany
    {
        return $this->hasMany(Recruiter::class);
    }
}
