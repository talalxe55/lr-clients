<?php


namespace App\Classes;

use App\Models\User;
use App\Models\Clients;
use App\Models\ClientServices;
use App\Models\ClientType;
use App\Models\Status;
use App\Models\Services;
use App\Models\ClientBilling;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Models\Activity as Logger;
use Validator;
use DB;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Billing {
    public function __construct() {
        $this->create();
    }
    public function create() {
        Log::info('Running..');
        $clients = Clients::where('status','=','1')->get();
        $now = Carbon::now();
        $year = $now->year;
        $month = $now->month;
        $days_in_month = $now->month($month)->daysInMonth;
        $day = $now->day;
        $week_of_year =  $now->weekOfYear;
        $day_of_week = $now->dayOfWeek;
        $hour = $now->hour;
        $minute = $now->minute;
        $second  = $now->second;
        $services_data = [];
        $data = [];
        // $test_arr = [];
        // $test_services_arr= [];
        // $test_services_arr['service_id'] = 1;
        // $test_services_arr['service_name'] = 'Website';
        // $test_services_arr['amount_type'] = 'percentage';
        // $test_services_arr['service_discount'] = 10;
        // $test_services_arr['service_amount'] = 3;
        // $test_services_arr['service_total'] = 3;
        // array_push($test_arr, $test_services_arr);
        // $test_services_arr['service_id'] = 5;
        // $test_services_arr['service_name'] = 'Blogs';
        // $test_services_arr['amount_type'] = 'fixed';
        // $test_services_arr['service_discount'] = 10;
        // $test_services_arr['service_amount'] = 190;
        // $test_services_arr['service_total'] = 190;
        // array_push($test_arr, $test_services_arr);
        // $test_services_arr['service_id'] = 6;
        // $test_services_arr['service_name'] = 'Hosting';
        // $test_services_arr['amount_type'] = 'fixed';
        // $test_services_arr['service_amount'] = 50;
        // $test_services_arr['service_total'] = 50;
         $client_total = 0;
        // array_push($test_arr, $test_services_arr);

        $client_exist = null;
        foreach($clients as $client){
            $days_count = 0;
            $client_services = ClientServices::
            Join('services', 'client_services.service_id', '=', 'services.id')
            ->select('services.id' , 'services.name', 'services.amount as amount', 'services.amount_type AS amount_type','client_services.discount', 'client_services.quantity')
            ->where('client_services.client_id' , '=', $client->id)
            ->get();
            $client_exists = ClientBilling::where('client_id','=',$client->id)->whereYear('created_at','=',$year)->whereMonth('created_at','=',$month)->first();
            if($client_exists){
                $days_count = $client_exists->days_count;
                $client_exists = $client_exists->toArray();
            }
            foreach($client_services as $services){
                if($services->amount!=null && $services->amount!=0 && $services->quantity!=0 && $services->quantity!=null){
                    $data['service_id'] = $services->id;
                    $data['service_name'] = $services->name;
                    $data['amount_type'] = $services->amount_type;
                    $data['service_quantity'] = $services->quantity;
                    $amount = $services->amount;
                    $quantity = $services->quantity;
                    $amount_type = $services->amount_type;
                    $service_total = 0;
                    $discount_amount = null;
                    
                    if($services->discount!==0 && $services->discount!==null){
                        $data['service_discount'] = $services->discount;
                        $discount_amount = $services->discount;
                    }

                    if($amount_type == 'fixed'){
                        $amount = $amount * $quantity;
                        $final_amount = $discount_amount?$amount - $discount_amount:$amount;
                        $data['service_amount'] = $final_amount;
                        $data['service_total'] = $final_amount;
                    }
                    else if($amount_type == 'percentage'){
                        if($discount_amount){
                            $amount  = $amount * $quantity;
                            $discount_value =  ($amount/100)*$discount_amount;
                            $amount = $amount - $discount_value;
                            $amount_in_days = $amount/$days_in_month;
                            $data['service_amount'] = $amount_in_days;
                            $data['service_total'] = $amount_in_days;
                        }
                        else{
                            $amount  = $amount*$quantity;
                            $amount_in_days = $amount/$days_in_month;
                            $data['service_amount'] = $amount_in_days;
                            $data['service_total'] = $amount_in_days;
                        }
                      
                    }
                    if($client_exists){
                        
                        $client_old_service = json_decode($client_exists['services']);
                        foreach($client_old_service as $arr){
                            if($arr->service_id===$data['service_id']){
                                if($data['amount_type'] == 'percentage')
                                {
                                    $data['service_amount'] += $arr->service_amount;
                                    //$arr->service_total = $arr->service_amount;
                                    $data['service_total'] = $data['service_amount'];
                                }
                                // //$arr->service_amount += $data['service_amount'];
                                // $data['service_amount'] += $arr->service_amount;
                                // //$arr->service_total = $arr->service_amount;
                                // $data['service_total'] = $data['service_amount'];
                            }
                        }
                    }

                    $client_total += $data['service_total'];

                    // $ids = array_column($test_arr,'service_id');
                    // dd($ids);
                    
                    // $index = array_search($data['service_id'], $ids);
                    // if($index){
                    //     //dd(array_search($index, $test_arr));
                    //     print_r($index);
                    //     print_r($ids);
                    //     print_r($test_arr);
                    //     dd($test_arr[$index]);
                    //     $data['service_amount'] += $test_arr[$index]['service_amount'];
                    //     $data['service_total'] = $data['service_amount'];
                        
                    // }
                    array_push($services_data, $data);
                }
            }
            //dd($services_data);
            if($client_exists){
                $client_old_service = json_decode($client_exists['services']);
                $old_ids = array_column($client_old_service,'service_id');
                $new_ids = array_column($services_data,'service_id');
                // print_r($new_ids);
                // dd($old_ids);
                $left_out_services = array_diff($old_ids,$new_ids);
                if(is_array($left_out_services)){
                    foreach($left_out_services as $service){
                        $found = array_search($service, $left_out_services);
                            array_push($services_data, $client_old_service[$found]);
                        
                    }
                }

            }
            if($client_exists){
               $done =  ClientBilling::where('client_id','=',$client->id)->whereYear('created_at','=',$year)->whereMonth('created_at','=',$month)->update(['services' => json_encode($services_data), 'days_count' => ++$days_count, 'total' => $client_total]);
            }
            else{
               $done = ClientBilling::create(['client_id' => $client->id, 'services' => json_encode($services_data), 'days_count' => ++$days_count, 'total' => $client_total]);
            }
            
            if($done){
                $data = [];
                $services_data = [];
                $client_total = 0;
            }
            //dd($services_data);
            // foreach($services_data as $service){
            //     $ids = array_column($test_arr,'service_id');
            //     //print_r($ids);
                
            //     $index = array_search($service['service_id'], $ids);
            //     if($index){
            //         $service['service_amount'] += $test_arr[$index]['service_amount'];
            //         $service['service_total'] = $service['service_amount'];
                    
            //     }
            //     //dd();
            // }
           // dd($services_data);
            //dd(array_intersect_key($test_arr, $services_data));
            // $ids = array_column($services_data,'service_id');
            // dd($ids);
            // dd(array_search(1, $ids));
        }
    }
}