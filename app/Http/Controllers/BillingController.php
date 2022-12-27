<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Clients;
use App\Models\ClientServices;
use App\Models\ClientType;
use App\Models\Status;
use App\Models\Services;
use App\Models\ClientBilling;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Models\Activity as Logger;
use Validator;
use DB;
use Session;
use Carbon\Carbon;

class BillingController extends Controller
{
    //
    public function __construct(){

    }

    public function editClientBilling(Request $request){
        try{
            $client_id = $request->route('clientid');
            $billing_id = $request->route('billingid');

            $billing_details = ClientBilling::where('id', '=' ,$billing_id)->where('client_id' , '=', $client_id)->first();
            $client = Clients::where('id', '=', $client_id)->first();
            if(!$client || !$billing_details)
            return back();

            $services = json_decode($billing_details->services);
            return view('pages.client-billing')->with('billing', $billing_details)->with('client', $client)->with('services', $services);
        }
        catch(Exception $e){

        }
    }
}
