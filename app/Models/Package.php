<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;
    public function subscription():HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function jobpost():HasMany
    {
        return $this->hasMany(JobPost::class);
    }
}
