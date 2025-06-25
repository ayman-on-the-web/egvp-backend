<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{

    const TYPE_ADMIN = "Admin";
    const TYPE_ORGANIZATION = "Organiztion";
    const TYPE_VOLUNTEER = "Volunteer";

    const IDENTIFICATION_NATIONAL_ID = "National ID";
    const IDENTIFICATION_COMMERCIAL = "Commercial Registeration Number";

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'profile_photo_base64',
        'identification_type',
        'identification_number',
        'user_type',
        'is_active',
        'active_until',
        'is_approved',
        'approved_at',
        'points',
        'skills',
        'details',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function profile_photo()
    {
        header("Content-type: image/png");

        $default_base64 = "PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhLS0gTGljZW5zZTogQ0MwLiBNYWRlIGJ5IFNWRyBSZXBvOiBodHRwczovL3d3dy5zdmdyZXBvLmNvbS9zdmcvMjIxMDI4L3VzZXItYXZhdGFyIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCA1MTIgNTEyIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MTIgNTEyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTI1NiwwQzExNC44MzcsMCwwLDExNC44MzcsMCwyNTZzMTE0LjgzNywyNTYsMjU2LDI1NnMyNTYtMTE0LjgzNywyNTYtMjU2UzM5Ny4xNjMsMCwyNTYsMHogTTI1Niw0OTAuNjY3CgkJCWMtNTAuODU5LDAtOTcuODU2LTE2LjQ0OC0xMzYuMzQxLTQ0LjA1M2w1NS4xMjUtMTguMzg5YzMzLjM2NS0xMy45MDksNDQuNDgtNjQsNDQuNDgtNzYuMjI0YzAtMy4yLTEuNDI5LTYuMjA4LTMuODgzLTguMjM1CgkJCWMtMTEuOTI1LTkuODM1LTI0LjU3Ni0yNi45MDEtMjQuNTc2LTM5LjE2OGMwLTE0LjM1Ny01Ljg2Ny0yMi41MDctMTEuNTg0LTI2LjQ5NmMtMi42NjctNy4zODEtNi45NzYtMjAuODIxLTcuMzM5LTI5LjI5MQoJCQljNS4zMTItMC41OTcsOS40NTEtNS4xMiw5LjQ1MS0xMC42MDN2LTU2Ljg5NmMwLTMwLjQ0MywyOS4wNzctNzQuNjY3LDc0LjY2Ny03NC42NjdjNDIuODM3LDAsNTQuMTIzLDE4LjQ1Myw1NS41NTIsMjUuNzQ5CgkJCWMtMC4zODQsMS4zNjUtMC41MzMsMi43MDktMC40MDUsMy45MDRjMC42MTksNS43ODEsNC45NDksOC41MzMsNy4yNzUsMTAuMDA1YzMuNjY5LDIuMzI1LDEyLjI0NSw3Ljc4NywxMi4yNDUsMzUuMDI5djU2Ljg5NgoJCQljMCw1LjkwOSwyLjg1OSwxMC4wMDUsOC43NDcsMTAuMDA1YzAuMTkyLDAuMTkyLDAuNDQ4LDAuNjYxLDAuNjgzLDEuMTMxYy0wLjUxMiw4LjUxMi00LjY1MSwyMS4zOTctNy4zMTcsMjguNzM2CgkJCWMtNS42OTYsMy45ODktMTEuNTg0LDEyLjEzOS0xMS41ODQsMjYuNDk2YzAsMTIuMjY3LTEyLjY1MSwyOS4zMzMtMjQuNTc2LDM5LjE2OGMtMi40NzUsMi4wMjctMy44ODMsNS4wNTYtMy44ODMsOC4yMzUKCQkJYzAsMTIuMjAzLDExLjEzNiw2Mi4zMTUsNDUuMjI3LDc2LjQ4bDU0LjM3OSwxOC4xMzNDMzUzLjg3Nyw0NzQuMjE5LDMwNi44NTksNDkwLjY2NywyNTYsNDkwLjY2N3ogTTQwOC4yNTYsNDM0LjIxOQoJCQljLTAuOTgxLTMuMTU3LTMuMjQzLTUuODY3LTYuNjEzLTYuOTk3bC01Ni4xNDktMTguNjg4Yy0xOS42MjctOC4xNzEtMjguNzM2LTM5LjU3My0zMC44NjktNTIuMTM5CgkJCWMxNC41MjgtMTMuNTA0LDI3Ljk0Ny0zMy42MjEsMjcuOTQ3LTUxLjc5N2MwLTYuMTY1LDEuNzQ5LTguNTU1LDEuNDA4LTguNjE5YzMuMzI4LTAuODMyLDYuMDM3LTMuMiw3LjMxNy02LjM3OQoJCQljMS4wNDUtMi42MjQsMTAuMjQtMjYuMDY5LDEwLjI0LTQxLjg3N2MwLTAuODUzLTAuMTA3LTEuNzI4LTAuMzItMi41ODFjLTEuMzQ0LTUuMzU1LTQuNDgtMTAuNzUyLTkuMTczLTE0LjEyM3YtNDkuNjY0CgkJCWMwLTMwLjcyLTkuMzY1LTQzLjU2My0xOS4yNDMtNTEuMDA4Yy0yLjIxOS0xNS4yNTMtMTguNTYtNDQuOTkyLTc2Ljc1Ny00NC45OTJjLTU5LjQ3NywwLTk2LDU1LjkxNS05Niw5NnY0OS42NjQKCQkJYy00LjY5MywzLjM3MS03LjgyOSw4Ljc2OC05LjE3MywxNC4xMjNjLTAuMjEzLDAuODMyLTAuMzIsMS43MDctMC4zMiwyLjU4MWMwLDE1LjgwOCw5LjE5NSwzOS4yNTMsMTAuMjQsNDEuODc3CgkJCWMxLjI4LDMuMTc5LDIuOTY1LDUuMjA1LDYuMjkzLDYuMDM3YzAuNjgzLDAuNDA1LDIuNDMyLDIuNzczLDIuNDMyLDguOTZjMCwxOC4xNzYsMTMuNDE5LDM4LjI5MywyNy45NDcsNTEuNzk3CgkJCWMtMi4xMzMsMTIuNTY1LTExLjE1Nyw0My45MjUtMzAuMTQ0LDUxLjg2MWwtNTYuODk2LDE4Ljk2NWMtMy4zOTIsMS4xMzEtNS42NTMsMy44NjEtNi42MzUsNy4wNAoJCQlDNTMuNDE5LDM5MS4xNjgsMjEuMzMzLDMyNy4zMTcsMjEuMzMzLDI1NmMwLTEyOS4zODcsMTA1LjI4LTIzNC42NjcsMjM0LjY2Ny0yMzQuNjY3UzQ5MC42NjcsMTI2LjYxMyw0OTAuNjY3LDI1NgoJCQlDNDkwLjY2NywzMjcuMjc1LDQ1OC42MDMsMzkxLjEyNSw0MDguMjU2LDQzNC4yMTl6Ii8+Cgk8L2c+CjwvZz4KPC9zdmc+Cg==";

        if (!$this->profile_photo_base64) {
            echo base64_decode($default_base64);
            exit;
        }

        echo base64_decode($this->profile_photo_base64);
        exit;
    }

    public function applications() {
        return $this->hasMany(Application::class, 'volunteer_id');
    }
}
