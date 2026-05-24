<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $table = 'schools';

    protected $fillable = [
        'membership_id',
        'school_name',
        'school_email',
        'school_phone_no',
        'city',
        'address',
        'priciple_name',
        'priciple_phone_no',
        'priciple_email',
    ];

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'school_id', 'id');
    }
}
