<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'image_path',
        'available_portions',
        'expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the staff member who posted this meal.
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all claims for this meal.
     */
    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    /**
     * Check if the meal is expired or has no portions left.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at->isPast() || $this->available_portions <= 0;
    }
}
