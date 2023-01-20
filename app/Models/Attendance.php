<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_count',
        'subject_id',
        'student_id'
    ];

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
