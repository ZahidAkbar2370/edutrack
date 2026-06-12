<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDocument extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'student_documents';

    protected $fillable = [
        'student_id',
        'document_title',
        'document_file',
    ];
}
