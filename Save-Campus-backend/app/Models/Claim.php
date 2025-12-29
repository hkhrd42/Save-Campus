<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'meal_id',
        'user_id',
    ];

    /**
     * Get the meal that was claimed.
     */
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    /**
     * Get the student who made the claim.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who made the claim (alias for student).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
