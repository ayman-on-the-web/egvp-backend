<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'event_id',
        'volunteer_id',
        'hours'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }
}
