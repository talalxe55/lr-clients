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
//use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Models\Activity as Logger;
use Spatie\Activitylog\Models\Activity;
use Validator;
use DB;
use Session;
use Carbon\Carbon;

class LogsController extends Controller
{
    //
    public function __construct(){

    }

    public function index(Request $request){
        $logs = new Activity;
        if($request->query('event')&&$request->query('event')!=='default'){
           $logs = $logs->where('event','=',$request->query('event'));
            
        }
        if($request->query('date')&&$request->query('date')!==''){
            $logs = $logs->whereDate('created_at','<=',date('Y-m-d',strtotime($request->query('date'))));
        }
        if($request->query('user') && $request->query('user')!="default"){
            $logs = $logs->where('causer_id','=',$request->query('user'))->where('causer_type','=','App\Models\User');
        }
        if($request->query('client')&&$request->query('client')!=='default'){
            $logs = $logs->where('subject_id','=',$request->query('client'))->where('subject_type','=','App\Models\Clients');
            
        }
        $logs = $logs->paginate(10);
        $clients = Clients::all();
        $users = User::all()->reject(function ($user) {
            return in_array("superadmin", $user->getRoleNames()->toArray()) == true;
        })->map(function ($user) {
            return $user;
        });
        return view('pages.logs')->with('logs',$logs)->with('users',$users)->with('clients',$clients);
    }
}
