<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruiter extends Model
{
    use HasFactory;

    public function category() {}

    public function job_post(): HasMany
    {
        return $this->hasMany(Apply::class);
    }
}
