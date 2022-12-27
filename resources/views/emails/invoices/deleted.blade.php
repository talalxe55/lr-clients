@component('mail::message')
<h2>Invoice #{{$invoice->id}} of {{date('M, Y',strtotime($invoice->month))}} deleted for {{$client->name}},</h2>
<div>
    <h2>Billing Details</h2>
    @if($client->name)<p>Client Name: {{$client->name}} </p>@endif
    @if($client->type)<p>Type: {{$client->ClientType()}} </p>@endif
    @if($invoice->month)<p>Month: {{date('M, Y',strtotime($invoice->month))}} </p>@endif
    @if($invoice->days_count)<p>Days Count: {{$invoice->days_count}} </p>@endif
    @if($invoice->billing_total)<p>Total: {{$invoice->billing_total}} </p>@endif
    <p>Deleted By: {{$user->email}}</p>

</div>
 
{{-- <p>Visit @component('mail::button', ['url' => $body['url_b']])
Laravel Tutorials
@endcomponent and learn more about the Laravel framework.</p> --}}
 
 
{{-- If you do not want to receive these emails then please change your password and report to NLS99 support!<br> --}}
 
Thanks,<br>
{{ config('app.name') }}<br>
@endcomponent