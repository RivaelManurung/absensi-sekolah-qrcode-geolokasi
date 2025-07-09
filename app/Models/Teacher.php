<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nuptk', 'full_name', 'phone_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function homeroomClass()
    {
        return $this->hasOne(Kelas::class, 'homeroom_teacher_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}