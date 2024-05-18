<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function jobpost(): BelongsTo
    {
        return $this->belongsTo(JobPost::class);
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
