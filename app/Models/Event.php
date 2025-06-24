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
        'image_base64',
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

    public function image()
    {
        header("Content-type: image/png");

        $default_base64 = "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";

        if (!$this->image_base64) {
            echo base64_decode($default_base64);
            exit;
        }

        echo base64_decode($this->image_base64);
        exit;
    }
}
