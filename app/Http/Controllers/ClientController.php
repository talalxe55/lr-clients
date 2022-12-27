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
use Mail;
use Carbon\Carbon;
use App\Mail\ClientCreated;
use App\Mail\ClientEdited;
use App\Mail\ClientStatusChanged;
use App\Mail\ClientDeleted;
use App\Mail\ClientServiceAdded;
use App\Mail\ClientServiceEdited;
use App\Mail\ClientServiceDeleted;
use App\Mail\ClientDeleteRequest;

class ClientController extends Controller
{
    //
    public function __construct(){

    }

    public function index(Request $request){
        
        $client_status = Status::all();
        $client_type = ClientType::all();
        $params = [];
        $all_clients = Clients::where('deleted','=',0)->get();
        // $clients = DB::table('clients')
        // ->Join('statuses', 'clients.status', '=', 'statuses.id')
        // ->join('client_types', 'clients.type', '=', 'client_types.id')
        // ->join('client_services', 'clients.id', '=', 'client_services.client_id')
        // ->join('services', 'services.id', '=' ,'client_services.service_id')
        // ->select('clients.id' , 'clients.name','statuses.name AS status', 'client_types.name as type', 'clients.live_at', 'clients.cancelled_at', DB::raw('GROUP_CONCAT(services.name SEPARATOR ",") as services'))
        // ->groupBy('clients.id','clients.name','statuses.name', 'client_types.name', 'clients.live_at', 'clients.cancelled_at');
        $clients= Clients::join('statuses', 'clients.status', '=', 'statuses.id')
        ->leftjoin('client_types', 'clients.type', '=', 'client_types.id')
        ->leftjoin('client_services', 'clients.id', '=', 'client_services.client_id')
        ->leftjoin('services', 'services.id', '=' ,'client_services.service_id')
        ->select('clients.id' , 'clients.name','statuses.name AS status', 'client_types.name as type', 'clients.live_at', 'clients.cancelled_at')
        ->selectRaw('GROUP_CONCAT(services.name SEPARATOR ",") as services')
        ->where('clients.deleted' , '=', 0)
        ->groupBy('clients.id','clients.name','statuses.name', 'client_types.name', 'clients.live_at', 'clients.cancelled_at');
        $attributes = $request->validate([
            'status' => 'string',
            'type' => 'string',
        ]);
        if($request->input('status') && $request->input('status')!=='default'){
            $clients->where('status','=',$request->input('status'));
            $params['status'] = !empty($request->input('status')) ? $request->input('status') : '';
            
        }
        if($request->type&&$request->type!=='default'){
            $clients->where('type','=',$request->input('type'));
            $params['type'] = !empty($request->input('type')) ? $request->input('type') : '';
        }
        if($request->live_date && $request->live_date!=""){
            $clients->whereDate('live_at','<=',date('Y-m-d',strtotime($request->live_date)));
            $params['live_date'] = !empty($request->input('live_date')) ? $request->input('live_date') : '';
            //dd($clients);
        }
        if($request->client&&$request->client!=='default'){
            $clients->where('client_id','=',$request->client);
            $params['client'] = !empty($request->input('client')) ? $request->input('client') : '';
            
        }
        
       
        //$clients = $clients->get();
        //dd($clients);
        $clients = $clients->paginate(10);
        
        //dd($clients);
            //return view('pages.clients')->with('clients' , $clients)->with('status' , $client_status)->with('types' , $client_type);
        

        // $clients = DB::table('clients')
        // ->Join('statuses', 'clients.status', '=', 'statuses.id')
        // ->join('client_types', 'clients.type', '=', 'client_types.id')
        // ->join('client_services', 'clients.id', '=', 'client_services.client_id')
        // ->join('services', 'services.id', '=' ,'client_services.service_id')
        // ->select('clients.id' , 'clients.name','statuses.name AS status', 'client_types.name as type', 'clients.live_at', 'clients.cancelled_at', DB::raw('GROUP_CONCAT(services.name SEPARATOR ",") as services'))
        // ->groupBy('clients.id','clients.name','statuses.name', 'client_types.name', 'clients.live_at', 'clients.cancelled_at')
        // ->paginate(2);


        return view('pages.clients',compact('params'))->with('clients' , $clients)->with('status' , $client_status)->with('types' , $client_type)->with('all_clients', $all_clients);
    }

    public function showClient(Request $request){
        try{
            $client_id = $request->route('clientid');
            $client = DB::table('clients')
            ->Join('statuses', 'clients.status', '=', 'statuses.id')
            ->join('client_types', 'clients.type', '=', 'client_types.id')
            ->select('clients.id' , 'clients.name','statuses.name AS status', 'client_types.name as type', 'clients.live_at', 'clients.cancelled_at','clients.website')
            ->where('clients.id' , '=', $client_id)
            ->get()->first();

            $client_services = DB::table('client_services')
            ->Join('services', 'client_services.service_id', '=', 'services.id')
            ->select('services.*', 'client_services.quantity', 'client_services.discount')
            ->where('client_services.client_id' , '=', $client_id)
            ->get();
            //dd($client_services);
            $cl_ids = $client_services->pluck('id')->toArray();
            $client_status = Status::all();
            $client_type = ClientType::all();
            $services = collect(Services::all());
            $client_billing = ClientBilling::where('client_id' , '=', $client_id)->paginate(10);
            // dd($client_billing);
            $services = Services::all()->reject(function ($user) use ($cl_ids) {
                return in_array($user->id,$cl_ids) === true;
            })->map(function ($user) {
                return $user;
            });
            $activity = Logger::where('subject_id','=',$client_id);
            if($request->query('log_event')&&$request->query('log_event')!=='default'){
                $activity->where('event','=',$request->query('log_event'));
                
            }
            $activity = $activity->paginate(10);
            //$services = new Services();
            //$services = $services->diff($client_services);
            //$servies = array_diff_assoc($services,$client_services);
            // $client_services = ClientServices::select()where('client_id', '=', $client->id);
            //$diff = null;
            //print_r($services);
            //dd($services);
            //$diff = $client_services->diff($services);
           // print_r($client_services);
            // foreach($client_services as $service){
            //     //print_r($services);
            //    // $a = array_diff($client_services,$services);
            //    // print_r($a);
            // }
            // $client_active_services = [];
            // foreach($client_services_id as $s){
                
            //     if(array_search($s->service_id,$client_services)){

            //     }
            // }
            //dd($client);
           // echo array_search("red",$a);
           //dd($client_services);
           //dd($services);
           //$available_services = $client_services->diff($services);
           //var_dump($available_services);
           //arr_1_final = array_diff($client_services, $services);
            //$arr_1_final = array_diff($client_services, $services);
            //print_r($available_services);
            // foreach($available_services as $services){
            //     //print_r($services);
            // }
            return view('pages.edit-client')->with('client' , $client)->with('client_status' , $client_status)->with('client_type' , $client_type)->with('services' , $services)->with('client_services' , $client_services)->with('activity' , $activity)->with('client_billing' , $client_billing);

        }
        catch(Exception $e){
            Session::flash('alert-warning', 'No client found with this id!'); 
            return redirect('/clients');

        }
    }

    public function addClient(Request $request){
        $client_status = Status::all();
        $client_type = ClientType::all();
        $services = Services::all();
        return view('pages.add-client')->with('status' , $client_status)->with('types' , $client_type)->with('services', $services);
    }

    public function searchClients(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'name' => 'string',
                
                ]);
            if($validator->fails()){
                return response()->json([
                'success' => false,
                'error' => $validator->messages()->toArray(),
                'validation_failed' => 'true'
                ], 400);
            }
            $clients = Clients::where('name','LIKE','%'.$request->name.'%')->get();
            return response()->json([
                'success' => true,
                'data' => $clients,
                'validation_failed' => 'false'
                ], 200);
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'error' => 'An error occurred!',
                'validation_failed' => 'true'
                ], 501);
        }
    }

    public function createClient(Request $request){
        // dd($request->all());
        $attributes = $request->validate([
            'name' => 'required|string',
            'status' => 'exists:App\Models\Status,id',
            'type' => 'exists:App\Models\ClientType,id',
            'live_date' => 'nullable|date_format:Y-m-d',
            'cancel_date' => 'nullable|date_format:Y-m-d',
            'services' => 'array|exists:App\Models\Services,id'
        ]);
        
        $data = ['name' => $request->name,'status' => $request->status, 'type' => $request->status, 'live_date' => $request->live_date, 'cancel_date' => $request->cancel_date];
        $arr_services = [];
        try{
            $client = Clients::create($data);
            if(is_array($request->services)){
            $arr_services = $request->services;
            foreach($request->services as $service){
                $client_services = ClientServices::create(['client_id' => $client->id, 'service_id' => $service]);
            }
        }
            Session::flash('alert-success', 'Client created successfully!');
            activity()
            ->causedBy(auth()->user())
            ->performedOn($client)
            ->event('client_created')
            ->withProperties(['services' => implode(",", $arr_services)])
            ->log('Client '.$client->name.' created by '.auth()->user()->email.' at '.Carbon::now().'.');
            Mail::to(env('APP_NOTIFICATIONS'))->queue(new ClientCreated(auth()->user(),$client));
            return redirect('/clients');
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while createing client!'); 
            return back();
        }
       // return view('pages.add-client')->with('status' , $client_status)->with('types' , $client_type)->with('services', $services);
    }

    public function updateClient(Request $request){
        $attributes = $request->validate([
            'name' => 'required|string',
            'website' => 'nullable|required|string',
            'status' => 'exists:App\Models\Status,id',
            'type' => 'exists:App\Models\ClientType,id',
            'live_at' => 'nullable|date_format:Y-m-d',
            'cancelled_at' => 'nullable|date_format:Y-m-d',
        ]);
        $client_id = $request->route('clientid');
        try{
            //check for status
            $old_client = Clients::where('id', '=', $client_id)->first();
            if($request->status != $old_client->status){
                if($request->status == 1){
                    $attributes['live_at'] = date('Y-m-d',strtotime(Carbon::now()));
                    activity()
                    ->causedBy(auth()->user())
                    ->performedOn($old_client)
                    ->event('client_live')
                    ->log('Client '.$request->name.' status was changed to live by '.auth()->user()->email.' at '.Carbon::now().'.');
                    Mail::to(env('APP_NOTIFICATIONS'))->queue(new ClientStatusChanged(auth()->user(),$old_client,'Live'));
                }
                else if($request->status == 2){
                    $attributes['cancelled_at'] = date('Y-m-d',strtotime(Carbon::now()));
                    activity()
                    ->causedBy(auth()->user())
                    ->performedOn($old_client)
                    ->event('client_cancelled')
                    ->log('Client '.$request->name.' status was changed to cancelled by '.auth()->user()->email.' at '.Carbon::now().'.');
                    Mail::to(env('APP_NOTIFICATIONS'))->queue(new ClientStatusChanged(auth()->user(),$old_client,'Cancelled'));
                }
                
            }
            $updated = Clients::where('id', '=', $client_id)->update($attributes);
            $client =  Clients::where('id', '=', $client_id)->first();
            activity()
            ->causedBy(auth()->user())
            ->performedOn($client)
            ->event('client_updated')
            ->withProperties($attributes)
            ->log('Client '.$client->name.' edited by '.auth()->user()->email.' at '.Carbon::now().'.');
            Mail::to(env('APP_NOTIFICATIONS'))->queue(new ClientEdited(auth()->user(),$client));
            Session::flash('alert-success', 'Client saved successfully!'); 
            return back();
        }
        catch (Exception $e){
            Session::flash('alert-warning', 'An error occurred while saving client!'); 
            return back();
        }
    }

    public function deleteClient(Request $request){
        try{
            $client_id = $request->route('clientid');
            $client = Clients::where('id','=',$client_id)->first();
            $client->services()->delete();
            $client->update(['deleted' => 1]);
            Mail::to(env('APP_NOTIFICATIONS'))->queue(new ClientDeleted(auth()->user(),$client));
            activity()
            ->causedBy(auth()->user())
            ->performedOn($client)
            ->event('client_deleted')
            ->log('Client '.$client->name.' deleted by '.auth()->user()->email.' at '.Carbon::now().'.');
            Session::flash('alert-success', 'Client deleted successfully!');
            return back();
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while deleting this client!');
        }
    }

    public function deleteClientrequest(Request $request){
        try{
            $client_id = $request->route('clientid');
            $client = Clients::where('id','=',$client_id)->first();
            if(!$client)
            {
                Session::flash('alert-success', 'Client not found!');
                return back();
            }
            Mail::to(env('APP_NOTIFICATIONS'))->queue(new ClientDeleteRequest(auth()->user(),$client));
            activity()
            ->causedBy(auth()->user())
            ->performedOn($client)
            ->event('client_delete_request')
            ->log('Client '.$client->name.' delete request was initiated by '.auth()->user()->email.' at '.Carbon::now().'.');
            Session::flash('alert-success', 'Client delete request send successfully!');
            return back();
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while sending the request!');
            return back();
        }
    }

    public function deleteClientservice(Request $request){
        try{
            $client_id = $request->route('clientid');
            $client = Clients::where('id','=',$client_id)->first();
            $service_id = $request->route('serviceid');
            $service = Services::where('id', '=', $service_id)->first();
            $delete_service = ClientServices::where('service_id','=',$service_id)->where('client_id','=',$client_id)->first()->delete();
            Session::flash('alert-success', 'Service deleted successfully!');
            activity()
            ->causedBy(auth()->user())
            ->performedOn($client)
            ->event('client_service_deleted')
            ->withProperties(['service' => $service->name])
            ->log('Service '.$service->name.' deleted by '.auth()->user()->email.' at '.Carbon::now().'.');
            Mail::to(env('APP_NOTIFICATIONS'))->queue(new ClientServiceDeleted(auth()->user(),$client,$service));
            return back();
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while deleting this service!'); 
            return back();
        }
        
    }

    public function editClientservice(Request $request){
        try{
            $client_id = $request->route('clientid');
            $client = Clients::where('id','=',$client_id)->first();
            $service_id = $request->route('serviceid');
            $service = Services::where('id', '=', $service_id)->first();
            if(!$client || !$service){
                Session::flash('alert-warning', 'Service or Client do not exist!'); 
                return back();
            }
            $attributes = $request->validate([
                'discount' => 'nullable|int',
                'quantity' => 'nullable|int'
            ]);
            // if($validator->fails()){
            //     Session::flash('alert-warning', 'Please provide valid amount/discount!');
            //     return back();
            // }
            $update_service = ClientServices::where('service_id','=',$service_id)->where('client_id','=',$client_id)->update(['discount' => $request->discount, 'quantity' => $request->quantity]);
            if($update_service)
            Session::flash('alert-success', 'Service edited successfully!');
            activity()
            ->causedBy(auth()->user())
            ->performedOn($client)
            ->event('client_service_updated')
            ->withProperties($attributes)
            ->log('Service '.$service->name.' edited by '.auth()->user()->email.' at '.Carbon::now().'.');
            Mail::to(env('APP_NOTIFICATIONS'))->queue(new ClientServiceEdited(auth()->user(),$client,$service,ClientServices::where('service_id','=',$service_id)->where('client_id','=',$client_id)->first()));
            return back();
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while updating this service!'); 
            return back();
        }
        
    }

    public function addClientservice(Request $request){
        try{
            $client_id = $request->route('clientid');
            $validator = Validator::make($request->all(),[
                'service' => 'required|exists:App\Models\Services,id',
                ]);
            if($validator->fails()){
                Session::flash('alert-warning', 'The following service do not exist!');
                return back();
            }
            $added = ClientServices::create(['client_id' => $client_id, 'service_id' => $request->service,'quantity' => 1]);
            $service = Services::where('id','=',$request->service)->first();
            $client = Clients::where('id','=',$client_id)->first();
            if($added)
            Session::flash('alert-success', 'Service added successfully!');
            activity()
            ->causedBy(auth()->user())
            ->performedOn($client)
            ->event('client_service_added')
            ->withProperties(['service' => $service->name])
            ->log('Service '.$service->name.' Added by '.auth()->user()->email.' at '.Carbon::now().'.');
            Mail::to(env('APP_NOTIFICATIONS'))->queue(new ClientServiceAdded(auth()->user(),$client,$service,$added));
            return back();
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while adding this service!'); 
            return back();
        }
        
    }
}
