@can('view logs')
<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <script src="{{ asset('assets') }}/js/flatpickr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <x-navbars.sidebar activePage="logs"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Logs"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <script>

            jQuery(document).ready(function(){
                var livedate_config =  {
              enableTime: false,
              dateFormat: "d-m-Y",
              maxDate: "today",
              defaultDate: ["@if((bool)strtotime(Request::get('date'))) {{ date('d-m-Y', strtotime(Request::get('date'))) }} @else {{\Carbon\Carbon::now()->format('d-m-Y')}} @endif "],
            };

            jQuery('.date').flatpickr(livedate_config);

            var canceldate_config =  {
              enableTime: false,
              dateFormat: "d-m-Y",
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
            $('.client').select2();
            $('.user').select2();
            })
          

              </script>
            <style>
            span.select2-selection span.select2-selection__arrow {
                top: 9px !important;
                right: 4px !important;
            }
            span.select2-selection {
                height: 43px !important;
                padding: 7px 0 !important;
                font-size: 14px !important;
                border: 2px solid #dee2e6 !important;
            }
            span.select2-selection span {
                color: #b5b5c3 !important;
            }
                </style>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Logs</h6>
                            </div>
                        </div>

                        <div class="card-body px-0 pb-2">
                            <div class="border-radius-lg pt-4 pb-3">
                                <h6 class="text-black text-capitalize ps-3">Filters</h6>
                                <form method="GET" action="{{route('logs.all')}}" class="ps-3">
                                    @csrf
                                    <div class="row">
                                    <div class="mb-3 col-md-2">
                                        <label class="form-label">Client</label>
                                        <select name="client" class="client form-select border-2 p-2">
                                            <option value="default">Select Client</option>
                                            
                                            @foreach ($clients as $client)
                                            @if(Request::get('client')==$client->id)
                                            <option selected value="{{$client->id}}">{{$client->name}}</option>
                                            @else
                                            <option value="{{$client->id}}">{{$client->name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @error('client')
                                        <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-md-2">
                                        <label class="form-label">User</label>
                                        <select name="user" class="user form-select border-2 p-2">
                                            <option value="default">Select User</option>
                                            
                                            @foreach ($users as $user)
                                            @if(Request::get('user')==$user->id)
                                            <option selected value="{{$user->id}}">{{$user->name}}</option>
                                            @else
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @error('user')
                                        <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-2">
                                        <label class="form-label">Date</label>
                                        {{-- <input type="date" name="live_date" class="form-control border border-2 p-2"> --}}
                                        <input type="text" placeholder="Select Date.." name="date" class="flatpickr flatpickr-input active mb-4 date form-control border border-2 p-2" value="{{ Request::get('live_date') }}">
                                        @error('date')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                    </div>
                                    <div class="mb-3 col-md-2">
                                        <label class="form-label">Events</label>
                                        <select class="form-select border-2 p-2 mb-3" name="event">
                                            <option selected value="default">Select Event</option>
                                            <option {{ Request::get('event') == "client_service_added" ? "selected" : "" }} value="client_service_added">Client Service Added</option>
                                            <option {{ Request::get('event') == "client_service_updated" ? "selected" : "" }} value="client_service_updated">Client Service Edited</option>
                                            <option {{ Request::get('event') == "client_service_added" ? "selected" : "" }} value="client_service_added">Client Service Added</option>
                                            <option {{ Request::get('event') == "client_service_deleted" ? "selected" : "" }} value="client_service_deleted">Client Service Deleted</option>
                                            <option {{ Request::get('event') == "client_updated" ? "selected" : "" }} value="client_updated">Client Edited</option>
                                            <option {{ Request::get('event') == "client_created" ? "selected" : "" }} value="client_created">Client Created</option>
                                            <option {{ Request::get('event') == "client_deleted" ? "selected" : "" }} value="client_deleted">Client Deleted</option>
                                            <option {{ Request::get('event') == "client_live" ? "selected" : "" }} value="client_live">Client Live</option>
                                            <option {{ Request::get('event') == "client_cancelled" ? "selected" : "" }} value="client_cancelled">Client Cancelled</option>
                                            <option {{ Request::get('event') == "invoice_deleted" ? "selected" : "" }} value="invoice_deleted">Invoice Deleted</option>
                                            <option {{ Request::get('event') == "invoice_created" ? "selected" : "" }} value="invoice_created">Invoice Created</option>
                                            <option {{ Request::get('event') == "service_created" ? "selected" : "" }} value="service_created">Service Created</option>
                                            <option {{ Request::get('event') == "service_deleted" ? "selected" : "" }} value="service_deleted">Service Deleted</option>
                                            <option {{ Request::get('event') == "service_updated" ? "selected" : "" }} value="service_updated">Service Updated</option>
                                        </select>
                                        @error('event')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                    </div>
                                    <div class="mb-0 col-md-3 d-flex align-items-center">
                                        <button type="submit" class="btn mb-0 btn-link bg-gradient-primary"
                                            data-original-title="" title="" data-bs-toggle="modal" data-bs-target="#edit-client">Apply Filters</button>
                                    </div>
                                </div>
                                </form>
                                
                            </div>
                            <div class="table-responsive p-0">
                                <table class="table align-items-center justify-content-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Description</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Event</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Module</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                                User</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                                Attributes</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                                Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach($logs as $client)
                                        @can('delete client')
                                        <div class="modal fade" id="user-{{$client->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <form method="POST" action="{{route('delete.client', $client->id)}}">
                                            @csrf
                                            <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Delete Client</button>
                                                </div>
                                            </div>
                                            </div>
                                        </form>
                                        </div>
                                        @endcan

                                        <tr>
                                            <td>
                                                <div class="d-flex px-2">
                                                    <div>
                                                        <img src="{{ asset('assets') }}/img/small-logos/logo-asana.svg"
                                                            class="avatar avatar-sm rounded-circle me-2"
                                                            alt="spotify">
                                                    </div>
                                                    <div class="my-auto">
                                                        <h6 class="mb-0 text-sm">{{$client->description}}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0">{{$client->event}}</p>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold">{{$client->SubjectName()}} - {{$client->SubjectType()}}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <span class="me-2 text-xs font-weight-bold">{{$client->getUser()}}</span>
                                                    {{-- <div>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-gradient-info"
                                                                role="progressbar" aria-valuenow="60"
                                                                aria-valuemin="0" aria-valuemax="100"
                                                                style="width: 60%;"></div>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <span class="me-2 text-xs font-weight-bold">{{$client->properties}}</span>
                                                    {{-- <div>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-gradient-info"
                                                                role="progressbar" aria-valuenow="60"
                                                                aria-valuemin="0" aria-valuemax="100"
                                                                style="width: 60%;"></div>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <span class="me-2 text-xs font-weight-bold">{{$client->created_at}}</span>
                                                    {{-- <div>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-gradient-info"
                                                                role="progressbar" aria-valuenow="60"
                                                                aria-valuemin="0" aria-valuemax="100"
                                                                style="width: 60%;"></div>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </td>
                                            {{-- <td><span class="text-xs font-weight-bold">{{$client->live_at}}</span></td> --}}
                                            {{-- <td><span class="text-xs font-weight-bold">{{$client->cancelled_at}}</span></td> --}}
                                            <td class="align-middle">
                                                {{-- <button class="btn btn-link text-secondary mb-0">
                                                    <i class="fa fa-ellipsis-v text-xs"></i>
                                                </button> --}}
                                                <a class="btn" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v text-xs" style="color:#e53270"></i>
                                                  </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item" href="{{route('edit.client',$client->id)}}">Edit</a>
                                                    @can('delete client')<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#user-{{$client->id}}">Delete</a>@endcan
                                                    {{-- <a class="dropdown-item" href="#">Something else here</a> --}}
                                                  </div>
                                                {{-- <a rel="tooltip" class="btn btn-success btn-link"
                                                href="{{route('edit.client',$client->id)}}" data-original-title=""
                                                title="">
                                                
                                                <i class="material-icons">edit</i> --}}
                                                
                                            </a>
                                            </td>
                                        </tr>

                                        @endforeach

                                    </tbody>
                                </table>
                                <div class="d-flex align-items-center justify-content-center text-center pt-5">
                                    <span class="me-2 text-xs font-weight-bold">{{$logs->withQueryString()->links('pagination::bootstrap-4')}}</span>
                                </div>
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
