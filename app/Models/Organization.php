<?php

namespace App\Models;

class Organization extends User
{
    protected $table = 'users';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->where('user_type', User::TYPE_ORGANIZATION);
        });
    }
}
