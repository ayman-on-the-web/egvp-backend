<?php

namespace App\Models;

class Volunteer extends User
{
    protected $table = 'users';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
            'active_until' => 'date',
            'approved_at' => 'datetime',
            'points' => 'float',
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->where('user_type', User::TYPE_VOLUNTEER);
        });
    }

    public function applications() {
        return $this->hasMany(Application::class);
    }

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }

    public function points() {
        $points = 0;
        
        foreach($this->attendances as $attendance) {
            $points = $points + $attendance->points();
        }

        return round($points, 2);
    }
}
