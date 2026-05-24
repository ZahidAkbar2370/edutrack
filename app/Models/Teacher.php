<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'teachers';

    protected $fillable = [
        'school_id',
        'teacher_name',
        'teacher_email',
        'teacher_phone_no',
        'teacher_photo',
        'teacher_address',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }
}
