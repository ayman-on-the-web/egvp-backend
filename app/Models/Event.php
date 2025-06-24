<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        return $this->belongsTo(User::class, 'organization_id');
    }

    public function volunteers() {
        $volunteers = DB::table('events')
        ->join('applications', 'events.id', '=', 'applications.event_id')
        ->join('users as volunteers', 'applications.volunteer_id', '=', 'volunteers.id')
        ->where('applications.is_approved', '=', true)
        ->where('applications.event_id', $this->id)
        ->selectRaw('volunteers.*')
        ->get()
        ->toArray();
        
        return  Volunteer::hydrate($volunteers);
    }

    public function participants() {
        $volunteers = DB::table('events')
        ->join('applications', 'events.id', '=', 'applications.event_id')
        ->join('users as volunteers', 'applications.volunteer_id', '=', 'volunteers.id')
        ->selectRaw('volunteers.*')
        ->where('applications.event_id', $this->id)
        ->get()
        ->toArray();
        
        $participants =  Participant::hydrate($volunteers);

        $attendances = $this->attendances;
        $applications = $this->applications;

        foreach($participants as $participant) {
            $attendance = $attendances->where('volunteer_id', $participant->id)->first();
            $application = $applications->where('volunteer_id', $participant->id)->first();

            $participant->event_id = $this->id;
            $participant->attendance_id = $attendance ? $attendance->id : null;
            $participant->application_id = $application ? $application->id : null;
        }

        return $participants;
    }

    public function applications() {
        return $this->hasMany(Application::class);
    }

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }

    public function make_decision($decision) {
        $this->update([
            'status' => $decision,
            'decision_at' => date('Y-m-d H:i:s'),
            'is_approved' => $decision == self::STATUS_APPROVED
        ]);

        return true;
    }

    //Will check if the logged-in user has applied to this event before
    public function auth_applied() {
        if (Auth::guest()) {
            return false;
        }
        return $this->applications()->where('volunteer_id', '=', Auth::id())->count('id') > 0;
    }

    //Returns the application status of the current user
    public function auth_application() {
        if (Auth::guest()) {
            return null;
        }

        $application = $this->applications()->where('volunteer_id', '=', Auth::id())->first();
        
        if (!$application) {
            return null;
        }

        return $application;
    }

    public function approve() {
        return $this->make_decision(self::STATUS_APPROVED);
    }

    public function reject() {
        return $this->make_decision(self::STATUS_REJECTED);
    }
}
