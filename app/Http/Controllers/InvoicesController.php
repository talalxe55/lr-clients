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
use App\Models\Invoices;
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
use App\Mail\InvoiceCreated;
use App\Mail\InvoiceDeleted;

class InvoicesController extends Controller
{
    //
    public function __construct(){

    }

    public function index(Request $request){
        try{
            $params = [];
            $invoices = Invoices::leftjoin('clients', 'invoices.client_id', '=', 'clients.id')
            // ->leftjoin('client_types', 'clients.type', '=', 'client_types.id')
            // ->leftjoin('client_services', 'clients.id', '=', 'client_services.client_id')
            // ->leftjoin('services', 'services.id', '=' ,'client_services.service_id')
            ->select('invoices.*', 'clients.name');
            // ->selectRaw('GROUP_CONCAT(services.name SEPARATOR ",") as services')
            // ->groupBy('clients.id','clients.name','statuses.name', 'client_types.name', 'clients.live_at', 'clients.cancelled_at');
            if($request->input('client')&&$request->input('client')!=='default'){
                $invoices->where('client_id','=',$request->input('client'));
                $params['client'] = $request->input('client');
                
            }
            if($request->input('type')&&$request->input('type')!=='default'){
                $invoices->where('type','=',$request->input('type'));
                $params['type'] = $request->input('type');
            }
            if($request->input('date') && $request->input('date')!=""){
                $invoices->whereDate('month','<=',date('Y-m-d',strtotime($request->input('date'))));
                $params['date'] = $request->input('date');
                //dd($clients);
            }
            
           
            //$clients = $clients->get();
            //dd($clients);
            //$clients = $clients->paginate(10);
            $invoices = $invoices->paginate(10);
            $clients = Clients::all();
            return view('pages.invoices', compact('params'))->with('invoices', $invoices)->with('clients', $clients);
        }
        catch(Exception $e){

        }
    }

    public function createInvoice(Request $request){
        try{
            
            $client_id = $request->route('clientid');
            $billing_id = $request->route('billingid');

            $client = Clients::where('id','=',$client_id)->first();
            $billing = ClientBilling::where('id','=',$billing_id)->first();

            if(!$client || !$billing){
                Session::flash('alert-warning', 'Billing or Client not found!');
                return back();
            }
    
            $attributes = $request->validate([
                'services' => 'required|array',
                'days_count' => 'required|int',
                'billing_total' => 'required|int',
                'billing_comments' => 'nullable|string|max:500',
                'month' => 'required|date'
            ]);
            $attributes['client_id'] = $client_id;
            $attributes['billing_id'] = $billing_id;
            $attributes['services'] = json_encode($request->services);
            $invoice_created = Invoices::create($attributes);
            if($invoice_created)
            {
                Session::flash('alert-success', 'Invoice created successfully!');
                activity()
                ->causedBy(auth()->user())
                ->event('invoice_created')
                ->performedOn($client)
                ->withProperties($attributes)
                ->log('Invoice of '.date('M, Y',strtotime($request->month)).' created by '.auth()->user()->email.' at '.Carbon::now().'.');
                Mail::to(env('APP_NOTIFICATIONS'))->queue(new InvoiceCreated(auth()->user(),$client,$invoice_created));
                return back();
            }
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while creating this invoice. Please try again!');
        }
    }

    public function deleteInvoice(Request $request){
        try{
            $invoice_id = $request->route('invoiceid');
            $client_id = $request->route('clientid');
            $client = Clients::where('id', '=', $client_id)->first();
            $invoice = Invoices::where('id', '=', $invoice_id)->where('client_id','=',$client_id)->first();
            if(!$invoice)
            return back();
            $invoice->delete();
            Session::flash('alert-success', 'Invoice deleted successfully!');
            activity()
            ->causedBy(auth()->user())
            ->event('invoice_deleted')
            ->performedOn($client)
            ->withProperties(['billing_id' => $invoice->billing_id, 'servcies' => $invoice->services,'total' => $invoice->billing_total])
            ->log('Invoice ID of '.$invoice_id.' for month of '.date('M, Y',strtotime($invoice->month)).' deleted by '.auth()->user()->email.' at '.Carbon::now().'.');
            Mail::to(env('APP_NOTIFICATIONS'))->queue(new InvoiceDeleted(auth()->user(),$client,$invoice));
            return back();
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while deleting this invoice!');
            return back();
            
        }
    }

    public function viewInvoice(Request $request){
        try{
            $invoice_id = $request->route('invoiceid');
            $invoice = Invoices::leftjoin('clients', 'invoices.client_id', '=', 'clients.id')
            // ->leftjoin('client_billings', 'invoices.billing_id', '=', 'client_billings.id')
            ->select('invoices.*', 'clients.*')
            ->where('invoices.id', '=', $invoice_id)->first();
            // $invoice = Invoices::where('id', '=', $invoice_id)->first();
            // $client = Clients::where('id','=',$invoice->client_id)->first();
            $services = json_decode($invoice->services);
            return view('pages.single-invoice')->with('invoice', $invoice)->with('services', $services);
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occurred while fetching this invoice!');
            return back();
        }
    }
}
