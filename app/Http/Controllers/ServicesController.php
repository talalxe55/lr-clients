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

class ServicesController extends Controller
{
    //
    public function __construct(){

    }

    public function index(){
        try{
            $params = [];
            $services = Services::paginate(10);
            //$servcies = $services->paginate(10);
            return view('pages.services.all-services', compact('params'))->with('services', $services);
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while fetching services!'); 
            return back();
        }
    }

    public function addService(Request $request){
        // $params = [];
        // $services = Services::paginate(10);
        //$servcies = $services->paginate(10);
        return view('pages.services.add-service');
    }

    public function viewService(Request $request){
        try{
            $id = $request->route('serviceid');
            $service = Services::where('id','=',$id)->first();
            if(!$service){
                Session::flash('alert-warning', 'Service not found or deleted!'); 
                return back();
            }
            return view('pages.services.edit-service')->with('service',$service);
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while fetching this service!'); 
            return back();
        }
    }

    public function createService(Request $request){
        try{
            $attributes = $request->validate([
                'name' => 'required|string',
                'amount' => 'nullable|int',
                'amount_type' => 'nullable|in:fixed,percentage',
            ]);
            $service = Services::create($attributes);
            activity()
            ->causedBy(auth()->user())
            ->performedOn($service)
            ->event('service_created')
            ->withProperties($attributes)
            ->log('Service '.$service->name.' created by '.auth()->user()->email.' at '.Carbon::now().'.');
            Session::flash('alert-success', 'Service created successfully!');
            return redirect('/services');
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while creating this service!'); 
            return back();
        }
    }

    public function updateService(Request $request){
        try{
            $id = $request->route('serviceid');
            $attributes = $request->validate([
                'name' => 'required|string',
                'amount' => 'nullable|int',
                'amount_type' => 'nullable|in:fixed,percentage',
            ]);
            $updated = Services::where('id','=',$id)->update($attributes);
            if($updated){
                $service = Services::where('id','=',$id)->first();
                activity()
                ->causedBy(auth()->user())
                ->performedOn($service)
                ->event('service_updated')
                ->withProperties($attributes)
                ->log('Service '.$service->name.' updated by '.auth()->user()->email.' at '.Carbon::now().'.');
                Session::flash('alert-success', 'Service updated successfully!');
                return back();
            }
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while updating this service!'); 
            return back();
    }
    }

    public function deleteService(Request $request){
        try{
            $id = $request->route('serviceid');
            $service = Services::where('id','=',$id)->first();
            $service->clients()->delete();
            $service_array = $service->toArray();
            $service->delete();
            activity()
            ->causedBy(auth()->user())
            ->performedOn($service)
            ->event('service_deleted')
            ->withProperties(json_encode($service_array))
            ->log('Service '.$service->name.' deleted by '.auth()->user()->email.' at '.Carbon::now().'.');
            Session::flash('alert-success', 'Service deleted successfully!');
            return back();
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while deleting this service!'); 
            return back();
        }
    }
}
