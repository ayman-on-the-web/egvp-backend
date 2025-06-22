<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'event_id',
        'volunteer_id',
        'hours',
        'points'
    ];

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($attendance) {
            if ($attendance->isDirty('hours')) {
                $attendance->points = $attendance->points();
                $attendance->save();

                $volunteer = $attendance->volunteer;
                $volunteer->points = $volunteer->points();
                $volunteer->save();
            }
        });
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function points()
    {

        if (!$this->hours) {
            return 0;
        }

        if (!$this->event->target_hours) {
            return 0;
        }

        return round(($this->hours / $this->event->target_hours) * $this->event->points, 2);
    }
}
