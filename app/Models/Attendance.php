<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'school_id',
        'class_id',
        'section_id',
        'student_id',
        'attendance_date',
        'attendance_status',
        'attendance_note',
        'whatsapp_status',
        'attendance_code',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public static function generateAttendanceCode()
    {
        return 'ATT-'.str_pad(Attendance::count() + 1, 6, '0', STR_PAD_LEFT);
    }
}
