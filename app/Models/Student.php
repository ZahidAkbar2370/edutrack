<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /** Public path when no profile image is uploaded */
    public const DEFAULT_PHOTO = 'images/default-student-profile.png';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_BANNED = 'banned';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_COMPLETED,
        self::STATUS_BANNED,
        self::STATUS_INACTIVE,
    ];

    protected $table = 'students';

    protected $fillable = [
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

    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
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

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // When student is soft-deleted, soft-delete related records too
    protected static function booted(): void
    {
        static::deleting(function (Student $student) {
            if ($student->isForceDeleting()) {
                $student->attendances()->withTrashed()->forceDelete();
                $student->dailyTests()->withTrashed()->forceDelete();
                ParentModel::withTrashed()->where('student_id', $student->id)->forceDelete();

                return;
            }

            $student->attendances()->delete();
            $student->dailyTests()->delete();
            $student->parent()->delete();
        });
    }

    // Full URL for profile image (uploaded or default placeholder)
    public function getPhotoUrlAttribute(): string
    {
        $path = $this->student_photo ?: self::DEFAULT_PHOTO;

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset($path);
    }
}
