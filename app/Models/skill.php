<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class skill extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'hobbies',
        'extra_curricular'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
