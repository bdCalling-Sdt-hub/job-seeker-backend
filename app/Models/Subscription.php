<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    public function package():BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function jobpost():BelongsTo
    {
        return $this->belongsTo(JobPost::class);
    }

}
