<?php

namespace App\Models;

class Organization extends User
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
            'approved_at' => 'date',
            'points' => 'float',
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->where('user_type', User::TYPE_ORGANIZATION);
        });
    }

    public function events() {
        return $this->hasMany(Event::class);
    }
}
