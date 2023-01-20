<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_semester');
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
