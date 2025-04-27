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
        'status',
        'decision_at'
    ];

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function make_decision($decision) {
        $this->update([
            'status' => $decision,
            'decision_at' => date('Y-m-d H:i:s')
        ]);

        return true;
    }

    public function approve() {
        return $this->make_decision(self::STATUS_APPROVED);
    }

    public function reject() {
        return $this->make_decision(self::STATUS_REJECTED);
    }
}
