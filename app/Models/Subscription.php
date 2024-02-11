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
<<<<<<< HEAD
=======

    // Abdur Rahman //

>>>>>>> 480dc518a1adff140653c5c9d847fe739cd793b2
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
