<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <script src="{{ asset('assets') }}/js/flatpickr.min.js"></script>
    <x-navbars.sidebar activePage="invoices"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="View Invoice"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <style>body{
            margin-top:20px;
            color: #484b51;
        }
        .text-secondary-d1 {
            color: #728299!important;
        }
        .page-header {
            margin: 0 0 1rem;
            padding-bottom: 1rem;
            padding-top: .5rem;
            border-bottom: 1px dotted #e2e2e2;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-pack: justify;
            justify-content: space-between;
            -ms-flex-align: center;
            align-items: center;
        }
        .page-title {
            padding: 0;
            margin: 0;
            font-size: 1.75rem;
            font-weight: 300;
        }
        .brc-default-l1 {
            border-color: #dce9f0!important;
        }
        
        .ml-n1, .mx-n1 {
            margin-left: -.25rem!important;
        }
        .mr-n1, .mx-n1 {
            margin-right: -.25rem!important;
        }
        .mb-4, .my-4 {
            margin-bottom: 1.5rem!important;
        }
        
        hr {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 1px solid rgba(0,0,0,.1);
        }
        
        .text-grey-m2 {
            color: #888a8d!important;
        }
        
        .text-success-m2 {
            color: #86bd68!important;
        }
        
        .font-bolder, .text-600 {
            font-weight: 600!important;
        }
        
        .text-110 {
            font-size: 110%!important;
        }
        .text-blue {
            color: #478fcc!important;
        }
        .pb-25, .py-25 {
            padding-bottom: .75rem!important;
        }
        
        .pt-25, .py-25 {
            padding-top: .75rem!important;
        }
        .bgc-default-tp1 {
            background-color: rgba(121,169,197,.92)!important;
        }
        .bgc-default-l4, .bgc-h-default-l4:hover {
            background-color: #f3f8fa!important;
        }
        .page-header .page-tools {
            -ms-flex-item-align: end;
            align-self: flex-end;
        }
        
        .btn-light {
            color: #757984;
            background-color: #f5f6f9;
            border-color: #dddfe4;
        }
        .w-2 {
            width: 1rem;
        }
        
        .text-120 {
            font-size: 120%!important;
        }
        .text-primary-m1 {
            color: #4087d4!important;
        }
        
        .text-danger-m1 {
            color: #dd4949!important;
        }
        .text-blue-m2 {
            color: #68a3d5!important;
        }
        .text-150 {
            font-size: 150%!important;
        }
        .text-60 {
            font-size: 60%!important;
        }
        .text-grey-m1 {
            color: #7b7d81!important;
        }
        .align-bottom {
            vertical-align: bottom!important;
        }</style>
        {{-- <script>

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
          

              </script> --}}
        <div class="container-fluid py-4">
            <div class="page-content container">
                <div class="page-header text-blue-d2">
                    <h1 class="page-title text-secondary-d1">
                        For {{date('M, Y', strtotime($invoice->month))}}
                        <small class="page-info">
                            <i class="fa fa-angle-double-right text-80"></i>
                           INV ID: #{{$invoice->id}}
                        </small>
                    </h1>
            
                    {{-- <div class="page-tools">
                        <div class="action-buttons">
                            <a class="btn bg-white btn-light mx-1px text-95" href="#" data-title="Print">
                                <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                                Print
                            </a>
                            <a class="btn bg-white btn-light mx-1px text-95" href="#" data-title="PDF">
                                <i class="mr-1 fa fa-file-pdf-o text-danger-m1 text-120 w-2"></i>
                                Export
                            </a>
                        </div>
                    </div> --}}
                </div>
            
                <div class="container px-0">
                    <div class="row mt-4">
                        <div class="col-12 col-lg-12">
                            {{-- <div class="row">
                                <div class="col-12">
                                    <div class="text-center text-150">
                                        <i class="fa fa-book fa-2x text-success-m2 mr-1"></i>
                                        <span class="text-default-d3">Bootdey.com</span>
                                    </div>
                                </div>
                            </div> --}}
                            <!-- .row -->
            
                            <hr class="row brc-default-l1 mx-n1 mb-4" />
            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div>
                                        <span class="text-sm text-grey-m2 align-middle">To:</span>
                                        <span class="text-600 text-110 text-blue align-middle">{{$invoice->name}}</span>
                                    </div>
                                    <div class="text-grey-m2">
                                        @if($invoice->website)
                                        <div class="my-1">
                                            Website: {{$invoice->website}}
                                        </div>
                                        @endif
                                        {{-- <div class="my-1">
                                            State, Country
                                        </div>
                                        <div class="my-1"><i class="fa fa-phone fa-flip-horizontal text-secondary"></i> <b class="text-600">111-111-111</b></div> --}}
                                    </div>
                                </div>
                                <!-- /.col -->
            
                                <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                                    <hr class="d-sm-none" />
                                    <div class="text-grey-m2">
                                        {{-- <div class="mt-1 mb-2 text-secondary-m1 text-600 text-125">
                                            Invoice
                                        </div> --}}
            
                                        <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Billing ID:</span> #{{$invoice->billing_id}}</div>
            
                                        <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Month:</span> {{date('M, Y', strtotime($invoice->month))}}</div>
            
                                        <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Days Calculated:</span> {{$invoice->days_count}}</div>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
            
                            <div class="mt-4">
                                <div class="row text-600 text-black text-bold bgc-default-tp1 py-25">
                                    <div class="d-none d-sm-block col-1">#</div>
                                    <div class="col-9 col-sm-5">Services</div>
                                    <div class="d-none d-sm-block col-4 col-sm-2">Qty</div>
                                    <div class="d-none d-sm-block col-sm-2">Amount</div>
                                    <div class="col-2">Total</div>
                                </div>
                                @php $counter = 0;@endphp
                                <div class="text-95 text-secondary-d3">
                                @foreach($services as $service)
                                <div class="row mb-2 mb-sm-0 py-25">
                                    <div class="d-none d-sm-block col-1">{{++$counter}}</div>
                                    <div class="col-9 col-sm-5">
                                    {{$service->service_name}}
                                    </div>
                                    <div class="d-none d-sm-block col-2">
                                    {{$service->service_quantity}}
                                    </div>
                                    <div class="d-none d-sm-block col-2 text-95">
                                    {{$service->service_amount}}
                                    </div>
                                    <div class="col-2 text-secondary-d2">
                                   {{$service->service_amount}}
                                    </div>
                                </div>
                                @endforeach
                                </div>
                                {{-- <div class="text-95 text-secondary-d3">
                                    <div class="row mb-2 mb-sm-0 py-25">
                                        <div class="d-none d-sm-block col-1">1</div>
                                        <div class="col-9 col-sm-5">Domain registration</div>
                                        <div class="d-none d-sm-block col-2">2</div>
                                        <div class="d-none d-sm-block col-2 text-95">$10</div>
                                        <div class="col-2 text-secondary-d2">$20</div>
                                    </div>
            
                                    <div class="row mb-2 mb-sm-0 py-25 bgc-default-l4">
                                        <div class="d-none d-sm-block col-1">2</div>
                                        <div class="col-9 col-sm-5">Web hosting</div>
                                        <div class="d-none d-sm-block col-2">1</div>
                                        <div class="d-none d-sm-block col-2 text-95">$15</div>
                                        <div class="col-2 text-secondary-d2">$15</div>
                                    </div>
            
                                    <div class="row mb-2 mb-sm-0 py-25">
                                        <div class="d-none d-sm-block col-1">3</div>
                                        <div class="col-9 col-sm-5">Software development</div>
                                        <div class="d-none d-sm-block col-2">--</div>
                                        <div class="d-none d-sm-block col-2 text-95">$1,000</div>
                                        <div class="col-2 text-secondary-d2">$1,000</div>
                                    </div>
            
                                    <div class="row mb-2 mb-sm-0 py-25 bgc-default-l4">
                                        <div class="d-none d-sm-block col-1">4</div>
                                        <div class="col-9 col-sm-5">Consulting</div>
                                        <div class="d-none d-sm-block col-2">1 Year</div>
                                        <div class="d-none d-sm-block col-2 text-95">$500</div>
                                        <div class="col-2 text-secondary-d2">$500</div>
                                    </div>
                                </div> --}}
            
                                <div class="row border-b-2 brc-default-l2"></div>
            
                                <!-- or use a table instead -->
                                <!--
                        <div class="table-responsive">
                            <table class="table table-striped table-borderless border-0 border-b-2 brc-default-l1">
                                <thead class="bg-none bgc-default-tp1">
                                    <tr class="text-white">
                                        <th class="opacity-2">#</th>
                                        <th>Description</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th width="140">Amount</th>
                                    </tr>
                                </thead>
            
                                <tbody class="text-95 text-secondary-d3">
                                    <tr></tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Domain registration</td>
                                        <td>2</td>
                                        <td class="text-95">$10</td>
                                        <td class="text-secondary-d2">$20</td>
                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                        -->
            
                                <div class="row mt-3">
                                    <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                                        Extra note such as company or payment information...
                                        
                                        <textarea id="w3review" name="billing_comments" readonly class="form-control border border-2 p-2" rows="4" cols="50">{{$invoice->billing_comments}}</textarea>
                                    </div>
                                    
            
                                    <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                                        {{-- <div class="row my-2">
                                            <div class="col-7 text-right">
                                                SubTotal
                                            </div>
                                            <div class="col-5">
                                                <input type="number" name="billing_subtotal" min="1" required class="form-control border border-2 p-2" value={{$billing->total}}>
                                                @error('billing_subtotal')
                                                <p class='text-danger inputerror'>{{ $message }} </p>
                                                @enderror
                                                
                                            </div>
                                        </div> --}}
            
                                        {{-- <div class="row my-2">
                                            <div class="col-7 text-right">
                                                Tax (10%)
                                            </div>
                                            <div class="col-5">
                                                <span class="text-110 text-secondary-d1">$225</span>
                                            </div>
                                        </div> --}}
            
                                        <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                            <div class="col-7 text-right">
                                                Total Amount
                                            </div>
                                            <div class="col-5">
                                                {{$invoice->billing_total}}
                                                {{-- <span class="text-150 text-black text-bold text-success-d3 opacity-10">${{$billing->total}}</span> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
            
                                <hr />
            
                                {{-- <div>
                                    <span class="text-secondary-d1 text-105">Generate this bill as an invoice</span>
                                    <button type="submit" class="bg-gradient-primary btn btn-info btn-bold px-4 float-right mt-3 mt-lg-0">Create Invoice</button>
                                </div> --}}
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
