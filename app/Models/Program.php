<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'faculty_id',
        'description',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }

    public function semesters()
    {
        return $this->belongsToMany(Semester::class, 'program_semester');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
