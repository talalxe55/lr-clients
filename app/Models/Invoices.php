<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'billing_id',
        'services',
        'days_count',
        'billing_total',
        'billing_comments',
        'month',
    ];
}
