<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasUuids, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'school_id',
        'membership_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'membership_expiry_date',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
