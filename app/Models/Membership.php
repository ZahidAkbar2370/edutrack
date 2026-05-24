<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'memberships';

    protected $fillable = [
        'membership_name',
        'membership_price',
        'students_limit',
        'teachers_limit',
        'allowed_attendance',
        'allowed_daily_test',
        'allowed_student_card',
        'allowed_whatsapp_message',
    ];
}
