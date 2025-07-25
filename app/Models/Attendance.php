<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id', 'schedule_id', 'recorded_by_teacher_id', 'date', 'status', 'notes', 'latitude', 'longitude'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}