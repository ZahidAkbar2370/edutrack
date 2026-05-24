<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasUuids;

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
