<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhatsappDevice extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $table = 'whatsapp_devices';

    protected $fillable = [
        'school_id',
        'user_id',
        'wachat_device_id',
        'wachat_device_number',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
