<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentModel extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'parents';

    protected $fillable = [
        'school_id',
        'student_id',
        'parent_name',
        'parent_phone_no',
        'parent_email',
        'parent_photo',
        'parent_cnic_front',
        'parent_cnic_back',
        'parent_address',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
