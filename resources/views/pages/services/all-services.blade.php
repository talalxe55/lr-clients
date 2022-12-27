@can('view services')
<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <script src="{{ asset('assets') }}/js/flatpickr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <x-navbars.sidebar activePage="services"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Services"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <script>

            jQuery(document).ready(function(){
                var livedate_config =  {
              enableTime: false,
              dateFormat: "d-m-Y",
              defaultDate: ["@if((bool)strtotime(Request::get('live_date'))) {{ date('d-m-Y', strtotime(Request::get('live_date'))) }} @else {{\Carbon\Carbon::now()->format('d-m-Y')}} @endif "],
            };

            jQuery('.live_date').flatpickr(livedate_config);

            var canceldate_config =  {
              enableTime: false,
              dateFormat: "d-m-Y",
              defaultDate: ["@if((bool)strtotime(Request::get('cancel_date'))) {{ date('d-m-Y', strtotime(Request::get('cancel_date'))) }} @else {{\Carbon\Carbon::now()->format('d-m-Y')}} @endif "],
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
                                <h6 class="text-white text-capitalize ps-3">Services</h6>
                            </div>
                        </div>

                        <div class="card-body px-0 pb-2">
                            @can('add service')
                            <div class=" me-3 my-3 text-end">
                                <a class="btn bg-gradient-dark mb-0" href="{{ route('add.service') }}"><i
                                        class="material-icons text-sm">add</i>&nbsp;&nbsp;Add New
                                    Service</a>
                            </div>
                            @endcan
                            {{-- <div class="border-radius-lg pt-4 pb-3">
                                <h6 class="text-black text-capitalize ps-3">Filters</h6>
                                <form method="POST" action="{{route('clients')}}" class="ps-3">
                                    @csrf
                                    <div class="row">
                                    <div class="mb-3 col-md-2">
                                        <label class="form-label">Clients</label>
                                        <select name="client" class="client form-select border-2 p-2">
                                            <option value="default">Select Client</option>
                                            
                                            @foreach ($all_clients as $client)
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
                                        <label class="form-label">Status</label>
                                        
                                        <select value="{{ Request::get('status') }}" name="status" class="form-select border-2 p-2">
                                            <option value="default">Select Status</option>
                                            @foreach ($status as $st)
                                            @if(Request::get('status')==$st->id)
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
                                    <div class="mb-3 col-md-2">
                                        <label class="form-label">Type</label>
                                        <select name="type" class="form-select border-2 p-2">
                                            <option value="default">Select Type</option>
                                            
                                            @foreach ($types as $type)
                                            @if(Request::get('type')==$type->id)
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
                                    <div class="mb-3 col-md-2">
                                        <label class="form-label">Live Date</label>
                                        <input type="text" placeholder="Select Date.." name="live_date" class="flatpickr flatpickr-input active mb-4 live_date form-control border border-2 p-2" value="{{ Request::get('live_date') }}">
                                        @error('live_date')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                    </div>
                                    <div class="mb-3 col-md-3 d-flex align-items-center">
                                        <button type="submit" class="btn mb-0 btn-link bg-gradient-primary"
                                            data-original-title="" title="" data-bs-toggle="modal" data-bs-target="#edit-client">Apply Filters</button>
                                    </div>
                                </div>
                                </form>
                                
                            </div> --}}
                            <div class="table-responsive p-0">
                                <table class="table align-items-center justify-content-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Name</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Amount</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Amount Type</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Active#</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                                Created at</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                                Updated at</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach($services as $service)
                                        @can('delete service')
                                        <div class="modal fade" id="service-{{$service->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <form method="POST" action="{{route('delete.service', $service->id)}}">
                                            @csrf
                                            <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Delete Service</button>
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
                                                        <h6 class="mb-0 text-sm">{{$service->name}}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0">{{$service->amount}}</p>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold">{{$service->amount_type}}</span>
                                            </td>
                                            <td>
                                                <span class="me-2 text-xs font-weight-bold mb-0">({{$service->serviceCount()}})</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <span class="me-2 text-xs font-weight-bold">{{$service->created_at}}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <span class="me-2 text-xs font-weight-bold">{{$service->updated_at}}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <a class="btn" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v text-xs" style="color:#e53270"></i>
                                                  </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    @can('edit service')<a class="dropdown-item" href="{{route('view.service',$service->id)}}">Edit</a>@endcan
                                                    @can('delete service')<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#service-{{$service->id}}">Delete</a>@endcan
                                                  </div>                                       
                                            </a>
                                            </td>
                                        </tr>

                                        @endforeach

                                    </tbody>
                                </table>
                                <div class="d-flex align-items-center justify-content-center text-center pt-5">
                                    <span class="me-2 text-xs font-weight-bold">{{$services->appends($params)->links('pagination::bootstrap-4')}}</span>
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
