<?php

namespace App\Models;

use App\Models\Apply;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    public function Recruiter()
    {
        return $this->belongsTo(Recruiter::class);
    }

    public function apply()
    {
        return $this->hasMany(Apply::class);
    }

}
