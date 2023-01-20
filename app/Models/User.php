<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles, InteractsWithMedia;

    const ADMIN = 'Admin';
    const STUDENT = 'Student';
    const TEACHER = 'Teacher';
    const HOD = 6;
    const COORDINATOR = 7;
    const STAFF = 8;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'date_of_birth',
        'address',
    ];

    protected $with = ['media'];

    protected $appends = ['role'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'datetime',
        'last_login_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function student_details()
    {
        return $this->hasOne(Student::class);
    }

    public function employees_details()
    {
        return $this->hasOne(Employee::class);
    }

    public function subjects()
    {
        return  $this->belongsToMany(Subject::class, 'subject_teacher', 'subject_id');
    }

    public function getRoleAttribute()
    {
        return $this->getRoleNames()->first();
    }
}
