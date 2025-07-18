<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id', 'subject_id', 'teacher_id', 'academic_year_id', 'day_of_week', 'start_time', 'end_time'
    ];

    public function class()
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // ✅ TAMBAHAN: Relasi ke Tahun Ajaran
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
    
    // ✅ TAMBAHAN: Relasi ke Absensi (untuk fitur hapus)
    public function attendances()
    {
        return $this->hasMany(Attendance::class); // Ganti "Attendance" jika nama model Anda berbeda
    }
}