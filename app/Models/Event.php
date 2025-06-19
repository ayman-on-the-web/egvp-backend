<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'Pending';
    const STATUS_REJECTED = 'Rejected';
    const STATUS_APPROVED = 'Approved';

    protected $fillable = [
        'start_date',
        'end_date',
        'target_hours',
        'points',
        'address',
        'city',
        'country',
        'is_approved',
        'status',
        'event_category_id',
        'organization_id',
    ];
    
    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function event_category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function volunteers() {
        return $this->hasMany(Volunteer::class);
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
}
