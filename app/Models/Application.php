<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    const STATUS_PENDING = 'Pending';
    const STATUS_REJECTED = 'Rejected';
    const STATUS_APPROVED = 'Approved';

    protected $fillable = [
        'volunteer_id',
        'event_id',
        'is_approved',
        'status',
        'decision_at'
    ];
    
    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function make_decision($decision) {
        $this->update([
            'status' => $decision,
            'decision_at' => date('Y-m-d H:i:s'),
            'is_approved' => $decision == self::STATUS_APPROVED
        ]);

        return true;
    }

    public function approve() {
        return $this->make_decision(self::STATUS_APPROVED);
    }

    public function reject() {
        return $this->make_decision(self::STATUS_REJECTED);
    }

    public function attendance() {
        $attendace = Attendance::where('event_id', $this->event_id)
        ->where('volunteer_id', $this->volunteer_id)
        ->first();
        
        return $attendace;
    }
}
