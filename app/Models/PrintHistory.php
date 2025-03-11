<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_name', 'recipient_phone', 'printed_by', 'printed_at',
    ];

    protected $dates = [
        'printed_at',
    ];
}
