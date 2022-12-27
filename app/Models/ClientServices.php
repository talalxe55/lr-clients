<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClientServices;

class ClientServices extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
        'quantity',
        'discount',
    ];

}
