@component('mail::message')
<h2>Service {{$service->name}} Modified For Client {{$client->name}},</h2>
<div>
    <h2>Service Details</h2>
    @if($service->name)<p>Name: {{$service->name}} </p>@endif
    @if($service->amount)<p>Amount: ${{$service->amount}} </p>@endif
    @if($service->amount_type)<p>Amount Type: {{$service->amount_type}} </p>@endif
    @if($edited_service->quantity)<p>Quantity: {{$edited_service->quantity}} </p>@endif
    @if($edited_service->discount)<p>Discount: {{$edited_service->quantity}} </p>@endif
    <p>Created at: {{date('d-m-Y H:i:s',strtotime($edited_service->created_at))}} </p>
    <p>Last Updated at: {{date('d-m-Y H:i:s',strtotime($edited_service->updated_at))}} </p>
    <p>Added By: {{$user->email}}</p>

</div>
 
{{-- <p>Visit @component('mail::button', ['url' => $body['url_b']])
Laravel Tutorials
@endcomponent and learn more about the Laravel framework.</p> --}}
 
 
{{-- If you do not want to receive these emails then please change your password and report to NLS99 support!<br> --}}
 
Thanks,<br>
{{ config('app.name') }}<br>
@endcomponent