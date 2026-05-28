<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonthlyFee extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'monthly_fees';

    protected $fillable = [
        'user_id',
        'school_id',
        'student_id',
        'payment_date',
        'payment_prove_image',
        'monthly_fee_amount',
        'any_fine_amount',
        'any_discount_amount',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'note',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function isFullyPaid(): bool
    {
        return (float) $this->remaining_amount <= 0;
    }
}
