<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Volunteer
{

    public $event_id = null, $application_id = null, $attendance_id = null;

    public function event() {
        return Event::find($this->event_id);
    }

    public function application() {
        return Application::find($this->application_id);
    }

    public function attendance() {
        return Attendance::find($this->attendance_id);
    }
}
