<?php

namespace App\Models;

use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    use HasFactory;

    public function job_post()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }
}
