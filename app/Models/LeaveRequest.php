<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id', 'approved_by_teacher_id', 'start_date', 'end_date', 'reason', 'attachment', 'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}