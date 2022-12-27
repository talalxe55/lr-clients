<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClientServices;
use App\Models\ClientBilling;
use App\Models\ClientType;
class Clients extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'website',
        'type',
        'live_at',
        'cancelled_at',
        'deleted'
    ];

    protected $casts = [
        'live_at' => 'datetime: dd/mm/YYYY',
        'cancelled_at' => 'datetime: dd/mm/YYYY'
    ];

    public function TotalservicesAmount(){
        $client_id = $this->id;
        $client_services = ClientServices::
        Join('services', 'client_services.service_id', '=', 'services.id')
        ->select('services.id' , 'services.name', 'services.amount as amount', 'services.amount_type AS amount_type','client_services.discount', 'client_services.quantity')
        ->where('client_services.client_id' , '=', $client_id)
        ->get();
        $total = 0;
        foreach($client_services as $services){
            $amount = $services->amount;
            $quantity = $services->quantity;
            $amount_type = $services->amount_type;
            $service_total = 0;
            $discount_amount = null;
            if($services->amount!=null && $services->amount!=0 && $services->quantity!=0 && $services->quantity!=null){
                if($services->discount!==0 && $services->discount!==null){
                    $data['service_discount'] = $services->discount;
                    $discount_amount = $services->discount;
                }

                if($amount_type == 'fixed'){
                    $amount = $amount * $quantity;
                    $final_amount = $discount_amount?$amount - $discount_amount:$amount;
                    $total+=$final_amount;
                }
                else if($amount_type == 'percentage'){
                    if($discount_amount){
                        $amount  = $amount * $quantity;
                        $discount_value =  ($amount/100)*$discount_amount;
                        $amount = $amount - $discount_value;
                        $total+=$amount;
                    }
                    else{
                        $amount  = $amount*$quantity;
                        $total+=$amount;
                    }
                  
                }
                }
            }
            return $total;
    }

    public function currentservicesAmount(){
        $client = ClientBilling::where('client_id','=',$this->id)->latest()->first();
        if($client){
            return '$'.$client->total.' For ('.$client->days_count.') Days of '.date('M, Y',strtotime($client->created_at));
        }
        else{
            return '$0';
        }
    }

    public function lastBillMonth(){
        $month = ClientBilling::where('client_id','=',$this->id)->max('created_at');
        return $month;
    }

    public function ClientType(){
        $type = ClientType::where('id','=',$this->type)->first();
        return $type->name;
    }

    public function activeServices(){
        try{
            $services = ClientServices::join('services', 'client_services.service_id','=','services.id')->select('services.name', 'services.amount', 'services.amount_type','client_services.quantity', 'client_services.discount')->where('client_services.client_id','=',$this->id)->get();
            return $services;
        }
        catch (Exception $e){
            return null;
        }
    }

    public function services()
    {
        return $this->hasMany(ClientServices::class, 'client_id');
        // return $this->belongsTo(ClientType::class, 'type');
    }

    public function type()
    {
        //return $this->hasOne(ClientType::class);
        //return $this->morphone(ClientServices::class, 'client_id');
        return $this->belongsTo(ClientType::class, 'type');
    }

    public function status()
    {
        return $this->hasOne(Status::class, 'status', 'id');
        //return 'live';
        //return $this->morphone(Status::class, 'status');
        //return $this->belongsTo(Status::class, 'status');
    }
}
