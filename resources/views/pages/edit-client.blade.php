@can('edit client')
<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <script src="{{ asset('assets') }}/js/flatpickr.min.js"></script>
    <x-navbars.sidebar activePage="clients"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Edit Client"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <script>

            jQuery(document).ready(function(){
                var livedate_config =  {
              enableTime: false,
              dateFormat: "Y-m-d",
              //minDate: new Date({{$client->live_at}}),
              //minDate: ["{{ date('Y-m-d', strtotime($client->live_at)) }} "],
            //   maxDate: "today",
              //defaultDate: new Date({{$client->live_at}})
              defaultDate: ["@if((bool)strtotime($client->live_at)) {{ date('Y-m-d', strtotime($client->live_at)) }} @endif "],
            };

            jQuery('.live_date').flatpickr(livedate_config);

            var canceldate_config =  {
              enableTime: false,
              dateFormat: "Y-m-d",
              //minDate: "today",
            //   maxDate: "today",
              //defaultDate: new Date({{$client->live_at}})
              defaultDate: ["@if((bool)strtotime($client->cancelled_at)) {{ date('Y-m-d', strtotime($client->cancelled_at)) }} @endif"],
            };

            jQuery('.cancel_date').flatpickr(canceldate_config);

            // function openCalender(dateclass) {
            //     let calendar = flatpickr(dateclass);
            //     calendar.toggle();
                
            // }

            $('.live-btn').on('click', function() {
                console.log('live')
                let calendar = flatpickr('.live_date');
                calendar.toggle();
                //jQuery('.live_date').click();
            })

            $('.cancel-btn').on('click', function() {
                console.log('cancel')
                let calendar = flatpickr('.cancel_date');
                calendar.toggle();
                //jQuery('.cancel_date').click();
            })
            // function openliveCalender() {
            //     //let calendar = flatpickr(dateclass);
            //     console.log(true);
            //     jQuery('.live_date').click();
                
            // }
            // function opencancelCalender() {
            //     //let calendar = flatpickr(dateclass);
            //     jQuery('.cancel_date').click();
                
            // }
            })
          

              </script>
        <div class="container-fluid py-4">
            <div class="modal fade" id="client-service" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <form method="POST" action="{{route('add.client.service', $client->id)}}">
                @csrf
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Type</label>
                            <select class="form-select border-2 p-2" name="service">
                                @foreach($services as $service)
                                <option data-service="{{json_encode($service)}}" value="{{$service->id}}">{{$service->name}}</option>
                                @endforeach
                            </select>
                            @error('type')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Service</button>
                    </div>
                </div>
                </div>
            </form>
            </div>
            {{-- Edit Modal For Client Services --}}
            @can('edit client service')
            @foreach ($client_services as $service)
            <div class="modal fade" id="service-{{$service->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <form method="POST" action="{{route('edit.client.service',['clientid' => $client->id , 'serviceid' => $service->id])}}">
                @csrf
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" min="1" required class="form-control border border-2 p-2" value={{$service->quantity}}>
                            @error('quantity')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        </div>

                        <div class="mb-3 col-md-12">
                            <label class="form-label">Discount @if($service->amount_type)({{$service->amount_type}}) @endif</label>
                            <input type="number" name="discount" class="form-control border border-2 p-2" value={{$service->discount}}>
                            @error('discount')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
                </div>
            </form>
            </div>
            @endforeach
            @endcan
            {{-- Billing Detail Modal --}}
            {{-- @foreach ($client_billing as $billing)
            <div class="modal fade" id="billing-{{$billing->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <form method="POST" action="{{route('edit.client.service',['clientid' => $client->id , 'serviceid' => $billing->id])}}">
                @csrf
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Billing Details For {{date('M-Y',strtotime($billing->created_at))}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" min="1" required class="form-control border border-2 p-2" value={{$service->quantity}}>
                            @error('quantity')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        </div>

                        <div class="mb-3 col-md-12">
                            <label class="form-label">Discount @if($service->amount_type)({{$service->amount_type}}) @endif</label>
                            <input type="number" name="discount" class="form-control border border-2 p-2" value={{$service->discount}}>
                            @error('discount')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
                </div>
            </form>
            </div>
            @endforeach --}}
            @can('update client')
            <div class="modal fade" id="edit-client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <form method="POST" action="{{route('update.client', $client->id)}}">
                @csrf
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control border border-2 p-2" value='{{ $client->name }}'>
                            @error('name')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        </div>

                        <div class="mb-3 col-md-12">
                            <label class="form-label">Website</label>
                            <input type="text" name="website" class="form-control border border-2 p-2" value='{{ $client->website }}'>
                            @error('website')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        </div>

                        <div class="mb-3 col-md-12">
                            <label class="form-label">Type</label>
                            <select class="form-select border border-2 p-2" name="type">
                                @foreach($client_type as $type)
                                @if($type->name===$client->type)
                                <option selected value="{{$type->id}}">{{$type->name}}</option>
                                @else
                                <option value="{{$type->id}}">{{$type->name}}</option>
                                @endif
                                @endforeach
                            </select>
                            @error('type')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        </div>

                        <div class="mb-3 col-md-12">
                            <label class="form-label">Change Status</label>
                            <select class="form-select border border-2 p-2" name="status">
                                @foreach($client_status as $st)
                                @if($st->name===$client->status)
                                <option selected value="{{$st->id}}">{{$st->name}}</option>
                                @else
                                <option value="{{$st->id}}">{{$st->name}}</option>
                                @endif
                                @endforeach
                            </select>
                            @error('status')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        </div>
                        @can('change client live date')
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Live Date</label>
                            {{-- <input type="date" name="live_date" class="form-control border border-2 p-2"> --}}
                            <input type="text" placeholder="Select Date.." name="live_at" class="flatpickr flatpickr-input active mb-4 live_date form-control border border-2 p-2">
                            @error('live_date')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        </div>
                        @endcan
                        @can('change client cancel date')
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Cancellation Date</label>
                            {{-- <input type="date" name="live_date" class="form-control border border-2 p-2"> --}}
                            <input type="text" placeholder="Select Date.." name="cancelled_at" class="flatpickr flatpickr-input active mb-4 cancel_date form-control border border-2 p-2">
                            @error('live_date')
                        <p class='text-danger inputerror'>{{ $message }} </p>
                        @enderror
                        </div>
                        @endcan

                        {{-- <div class="form-control">
                            <select class="form-select" name="service">
                            @foreach($services as $service)
                            <option value="{{$service->id}}">{{$service->name}}</option>
                            @endforeach
                        </select></div> --}}
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
                </div>
            </form>
            </div>
            @endcan
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6" style="display: flex;align-items: center;"><h6>{{$client->name}} - {{$client->type}} ({{$client->status}})</h6></div>
                        <div class="col-lg-6" style="text-align:right"><button type="button" class="btn btn-link bg-gradient-primary"
                            data-original-title="" title="" data-bs-toggle="modal" data-bs-target="#edit-client">Edit Client</button></div>
                        <div class="col-xl-6">
                            <div class="row">
                                <div class="col-md-6 col-6">
                                    <div class="card mb-1">
                                        <div class="card-body mt-3 mb-3 pt-0 p-3 text-center">
                                            <h6 class="text-center mb-0">Live Date</h6>
                                            <hr class="horizontal dark my-3">
                                            <h5 class="mb-0">{{$client->live_at}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="card mb-1">
                                        <div class="card-body mt-3 mb-3 pt-0 p-3 text-center">
                                            <h6 class="text-center mb-0">Cancellation Date</h6>
                                            <hr class="horizontal dark my-3">
                                            <h5 class="mb-0">{{$client->cancelled_at}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @can('view client billing')
                            <div class="col-lg-6 mb-2">
                               <div class="card h-100">
                                   <div class="card-header pb-0 p-3">
                                       <div class="row">
                                           <div class="col-6 d-flex align-items-center">
                                               <h6 class="mb-0">Billing</h6>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="card-body p-3 pb-0">
                                       <ul class="list-group">
                                        @foreach ($client_billing as $billing)
                                        <li
                                        class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark font-weight-bold text-sm">{{date('M-Y', strtotime($billing->created_at))}} For ({{$billing->days_count}}) days</h6>
                                        </div>
                                        <div class="d-flex align-items-center text-sm">
                                            ${{$billing->total}}
                                                @can('generate client billing')<a href="{{route('edit.client.billing', ['clientid' => $client->id , 'billingid' => $billing->id])}}"  class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="material-icons text-lg position-relative me-1">picture_as_pdf</i>See Details</a>
                                                @endcan
                                        </div>
                                    </li>
                                        @endforeach
                                       </ul>
                                       <div class="d-flex align-items-center justify-content-center text-center pt-5">
                                        <span class="me-2 text-xs font-weight-bold">{{$client_billing->links('pagination::bootstrap-4')}}</span>
                                    </div>
                                   </div>
                               </div>
                           </div>
                           @endcan
                    </div>
                    @can('view client service')
                    <div class="card">
                        <div class="row m-0">
                        <div class="card-header pb-0 px-3 col-lg-6 p-0" style="display: flex; align-items: center;">
                            <h6 class="mb-0">Client Services</h6>          
                        </div>
                        @can('add client service')
                        <div class="col-lg-6" style="text-align: right">
                            <button type="button" class="btn btn-link bg-gradient-primary mt-3"
                            data-original-title="" title="" data-bs-toggle="modal" data-bs-target="#client-service">Add New Service..</button>
                        </div>
                        @endcan
                        </div>
                        
                            <div class="card-body pt-4 p-3">
                            <ul class="list-group">
                                @foreach($client_services as $service)
                                <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-3 text-sm">{{$service->name}} 
                                        @if($service->amount) 
                                        For 
                                        @if($service->discount)
                                        @if($service->amount_type=='percentage')
                                        @php 
                                        $discount = ($service->amount*$service->quantity/100)*$service->discount;
                                        $amount = $service->amount - $discount;
                                        @endphp 
                                        ${{$amount}}
                                        @else
                                        @php
                                        $amount = ($service->amount*$service->quantity) - $service->discount
                                        @endphp
                                        ${{$amount}}
                                        @endif
                                        @else
                                        ${{$service->amount}}
                                        @endif
                                        @endif
                                        </h6>
                                        @if($service->discount)
                                        <span class="mb-2 text-xs">Discount: 
                                            <span class="text-dark font-weight-bold ms-sm-2">@if($service->amount_type == 'fixed') Fixed ${{$service->discount}} @else {{$service->discount}}% @endif</span></span>
                                        @endif
                                        @if($service->amount_type)
                                        <span class="mb-2 text-xs">Amount Type: <span
                                            class="text-dark ms-sm-2 text-capitalize font-weight-bold">@if($service->amount_type=='percentage') Per Day @else {{$service->amount_type}} @endif</span></span>
                                        @endif
                                        @if($service->quantity)
                                        <span class="mb-2 text-xs">Quantity: <span
                                            class="text-dark ms-sm-2 font-weight-bold">{{$service->quantity}}</span></span>
                                        @endif
                                        @if($service->created_at)
                                        <span class="mb-2 text-xs">Added on: <span
                                            class="text-dark ms-sm-2 font-weight-bold">{{$service->created_at}}</span></span>
                                        @endif
                                        @if($service->updated_at)
                                        <span class="mb-2 text-xs">Last updated: <span
                                            class="text-dark ms-sm-2 font-weight-bold">{{$service->updated_at}}</span></span>
                                        @endif
                                    </div>
                                    <div class="ms-auto text-end">
                                        @can('delete client service')
                                        <a class="btn btn-link text-danger text-gradient px-3 mb-0" href="{{route('delete.client.service',['clientid' => $client->id , 'serviceid' => $service->id])}}"><i
                                                class="material-icons text-sm me-2">delete</i>Delete</a>
                                        @endcan
                                        @can('edit client service')
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#service-{{$service->id}}" class="btn btn-link text-dark px-3 mb-0"><i
                                                class="material-icons text-sm me-2">edit</i>Edit</a>
                                        @endcan

                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endcan

                </div>

            </div>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <div class="card h-100 mb-4">
                        <div class="card-header pb-0 px-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-0">Client Logs</h6>
                                </div>
                                <div class="col-md-3">
                                    
                                      <form method="GET" action="{{route('edit.client', $client->id)}}">
                                        @csrf
                                        <select class="form-select border-2 p-2" name="log_event">
                                            <option selected disabled value="default">Select Event</option>
                                            <option value="client_service_added">Service Added</option>
                                            <option value="client_service_updated">Service Edited</option>
                                            <option value="client_service_added">Service Added</option>
                                            <option value="client_service_deleted">Service Deleted</option>
                                            <option value="client_updated">Client Cdited</option>
                                            <option value="client_created">Client Created</option>
                                            <option value="client_live">Client Live</option>
                                            <option value="client_cancelled">Client Cancelled</option>
                                            <option value="invoice_deleted">Invoice Deleted</option>
                                            <option value="invoice_created">Invoice Created</option>
                                        </select>
                                        
                                        @error('type')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                    
                                </div>
                                <div class="mb-3 col-md-3 d-flex align-items-center">
                                    <button type="submit" class="btn mb-0 btn-link bg-gradient-primary">Apply Filters</button>
                                </div>
                            </form>
                            </div>
                        </div>
                        <div class="card-body pt-4 p-3">  
                            <ul class="list-group">
                                @foreach($activity as $a)
                                <li
                                class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <button
                                        class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 p-3 btn-sm d-flex align-items-center justify-content-center"><i
                                            class="material-icons text-lg">expand_less</i></button>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">{{$a->description}}</h6>
                                        
                                        @foreach($a->properties as $key => $value)
                                        {{-- @if(is_array($value))
                                        @foreach($value as $k => $v)
                                        <span class="text-xs">{{$k}} => {{$v}}</span>
                                        @endforeach
                                        @else
                                        <span class="text-xs">{{$key}} => {{$value}}</span>
                                        @endif --}}
                                        <span class="text-xs">{{$key}} => {{$value}}</span>
                                        @endforeach
                                        <span class="text-xs">at {{$a->created_at}}</span>
                                    </div>
                                </div>
                            </li>

                                @endforeach
                            </ul>
                            <div class="d-flex align-items-center justify-content-center text-center pt-5">
                                <span class="me-2 text-xs font-weight-bold">{{$activity->links('pagination::bootstrap-4')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-footers.auth></x-footers.auth>
        </div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
@endcan