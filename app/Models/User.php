<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    protected $fillable = [
        'fullName',
        'email',
        'password',
        'userType',
        'otp',
        'verify_email'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function subscription():HasMany

    {
        return $this->hasMany(Subscription::class);
    }

    public function candidate():HasMany
    {
        return $this->HasMany(Candidate::class);
    }
    public function experience():HasMany
    {
        return $this->hasMany(Experience::class);
    }
    public function interest():HasMany
    {
        return $this->HasMany(Interest::class);
    }
    public function education():HasMany
    {
        return $this->HasMany(Education::class);
    }
    public function training():HasMany
    {
        return $this->hasMany(Training::class);
    }
}
