<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyTest extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'daily_tests';

    protected $fillable = [
        'school_id',
        'class_id',
        'section_id',
        'student_id',
        'teacher_id',
        'daily_test_date',
        'daily_test_name',
        'subject',
        'daily_test_obtained',
        'daily_test_total',
        'daily_test_percentage',
        'daily_test_note',
        'whatsapp_status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
}
