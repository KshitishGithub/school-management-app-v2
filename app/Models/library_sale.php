<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class library_sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'session',
        'class_id',
        'registration_id',
        'subject_id',
        'book_id',
        'quantity',
        'price',
        'total',
    ];
}
