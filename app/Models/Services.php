<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClientServices;

class Services extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'amount_type',
    ];

    public function serviceCount(){
        $active_clients = ClientServices::where('service_id','=',$this->id)->get();
        return $active_clients->count();
    }

    public function clients()
    {
        return $this->hasMany(ClientServices::class, 'service_id');
        // return $this->belongsTo(ClientType::class, 'type');
    }
}
