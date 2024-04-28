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

    // Package.php

    public function post_limit_exceeded()
    {
        // Assuming you have a relationship with the jobs table
        $totalPosts = $this->jobpost()->where('user_id',auth()->user()->id)->count();
        return $totalPosts >= $this->post_limit;
    }

    public function hasExpired()
    {
        $latestSubscription = $this->subscription()->latest()->first();
        return now()->greaterThanOrEqualTo($latestSubscription->end_date);
    }
}
