<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Student extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'students';

    protected $fillable = [
        "user_id",
        'school_id',
        'class_id',
        'section_id',
        'student_name',
        'student_email',
        'student_phone_no',
        'student_photo',
        'student_roll_number',
        'student_admission_date',
        'status',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(ParentModel::class, 'student_id', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id', 'id');
    }

    public function dailyTests()
    {
        return $this->hasMany(DailyTest::class, 'student_id', 'id');
    }


    public static function generateRollNumber()
    {
        $lastStudent = Student::withTrashed()->where('school_id', Auth::user()->school_id)->orderBy('created_at', 'desc')->first();

        if ($lastStudent) {
            return $lastStudent->student_roll_number + 1;
        }

        return 1;
    }
}
