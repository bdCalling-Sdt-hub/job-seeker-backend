<?php

namespace App\Models;

use App\Models\JobPost;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    use HasFactory;

    public function job_post()
    {
        return $this->belongsTo(JobPost::class);
    }
}
