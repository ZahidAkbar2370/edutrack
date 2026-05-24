<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'transactions';

    protected $fillable = [
        'school_id',
        'transaction_purpose',
        'membership_id',
        'transaction_amount',
        'transaction_note',
    ];
}
