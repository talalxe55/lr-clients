@can('add service')
<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <script src="{{ asset('assets') }}/js/flatpickr.min.js"></script>
    <x-navbars.sidebar activePage="services"></x-navbars.sidebar>
    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage='Add Service'></x-navbars.navs.auth>
        <!-- End Navbar -->

        <script>

            jQuery(document).ready(function(){
                var livedate_config =  {
              enableTime: false,
              dateFormat: "Y-m-d",
            };

            jQuery('.live_date').flatpickr(livedate_config);

            var canceldate_config =  {
              enableTime: false,
              dateFormat: "Y-m-d",
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

        <div class="container-fluid px-2 px-md-4">
            <div class="page-header min-height-300 border-radius-xl mt-4"
                style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');">
                <span class="mask  bg-gradient-primary  opacity-6"></span>
            </div>
            <div class="card card-body mx-3 mx-md-4 mt-n6">
                <div class="row gx-4 mb-2">
                    {{-- <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            <img src="{{ asset('assets') }}/img/bruce-mars.jpg" alt="profile_image"
                                class="w-100 border-radius-lg shadow-sm">
                        </div>
                    </div> --}}
                    {{-- <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ auth()->user()->name }}
                            </h5>
                            <p class="mb-0 font-weight-normal text-sm">
                                {{  }}
                            </p>
                        </div>
                    </div> --}}
                    {{-- <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 active " data-bs-toggle="tab" href="javascript:;"
                                        role="tab" aria-selected="true">
                                        <i class="material-icons text-lg position-relative">home</i>
                                        <span class="ms-1">App</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" href="javascript:;"
                                        role="tab" aria-selected="false">
                                        <i class="material-icons text-lg position-relative">email</i>
                                        <span class="ms-1">Messages</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" href="javascript:;"
                                        role="tab" aria-selected="false">
                                        <i class="material-icons text-lg position-relative">settings</i>
                                        <span class="ms-1">Settings</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                </div>
                <div class="card card-plain h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                            <div class="col-md-8 d-flex align-items-center">
                                <h6 class="mb-3">Add New Service</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        @if (session('status'))
                        <div class="row">
                            <div class="alert alert-success alert-dismissible text-white" role="alert">
                                <span class="text-sm">{{ Session::get('status') }}</span>
                                <button type="button" class="btn-close text-lg py-3 opacity-10"
                                    data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        @endif
                        @if (Session::has('demo'))
                                <div class="row">
                                    <div class="alert alert-danger alert-dismissible text-white" role="alert">
                                        <span class="text-sm">{{ Session::get('demo') }}</span>
                                        <button type="button" class="btn-close text-lg py-3 opacity-10"
                                            data-bs-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                        @endif
                        <form method='POST' action='{{ route('create.service') }}'>
                            @csrf
                            <div class="row">
                               
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control border border-2 p-2" required value="{{old('name')}}">
                                    @error('name')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                </div>
                               
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Amount($)</label>
                                    <input type="number" name="amount" class="form-control border border-2 p-2" required value="{{old('amount')}}">
                                    @error('amount')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                </div>
                            </div>
                                    <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="floatingTextarea2">Amount Type</label>
                                    <select required class="text-capitalize form-select border border-2 p-2"
                                        placeholder="Amount Type" id="floatingTextarea2" name="amount_type"
                                        rows="4" cols="50">
                                        <option disabled>Select Type</option>
                                        <option class="text-capitalize" value="fixed">Fixed</option>
                                        <option class="text-capitalize" value="percentage">Percentage</option>
                                    </select>
                                        @error('amount_type')
                                        <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-block bg-gradient-dark">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
        <x-footers.auth></x-footers.auth>
    </div>
    <x-plugins></x-plugins>

</x-layout>
@endcan
