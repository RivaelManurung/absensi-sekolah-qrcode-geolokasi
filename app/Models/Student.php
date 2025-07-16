<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'class_id', 'nisn', 'nis', 'full_name', 'gender', 'date_of_birth', 'photo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        // Pastikan nama model adalah 'Kelas' sesuai controller
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    // Relasi lainnya sudah benar
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}