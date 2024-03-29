<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookMark extends Model
{
    use HasFactory;

    public function job_post():BelongsTo
    {
        return $this->belongsTo(JobPost::class);
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
