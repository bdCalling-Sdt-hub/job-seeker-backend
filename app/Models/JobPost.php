<?php

namespace App\Models;

use App\Models\Apply;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPost extends Model
{
    use HasFactory;

    public function apply()
    {
        return $this->hasMany(Apply::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function recruiter():BelongsTo
    {
        return $this->belongsTo(Recruiter::class);
    }

    public function book_marks():HasMany
    {
        return $this->HasMany(BookMark::class);
    }

    //category -> jobpost
    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

}
