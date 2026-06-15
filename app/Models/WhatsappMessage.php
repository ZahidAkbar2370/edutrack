<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhatsappMessage extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $table = 'whatsapp_messages';

    protected $fillable = [
        'school_id',
        'student_id',
        'teacher_id',
        'parent_id',
        'message_type',
        'from_number',
        'to_number',
        'message',
        'status',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id', 'id');
    }
}
