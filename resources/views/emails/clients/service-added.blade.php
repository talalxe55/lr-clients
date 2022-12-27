@component('mail::message')
<h2>New Service Added For Client {{$client->name}},</h2>
<div>
    @if($service->name)<p>Service Name: {{$service->name}} </p>@endif
    @if($added_service->quantity)<p>Service Quantity: {{$added_service->quantity}} </p>@endif
    <p>Created at: {{date('d-m-Y H:i:s',strtotime($added_service->created_at))}} </p>
    <p>Last Updated at: {{date('d-m-Y H:i:s',strtotime($added_service->updated_at))}} </p>
    <p>Added By: {{$user->email}}</p>

</div>
 
{{-- <p>Visit @component('mail::button', ['url' => $body['url_b']])
Laravel Tutorials
@endcomponent and learn more about the Laravel framework.</p> --}}
 
 
{{-- If you do not want to receive these emails then please change your password and report to NLS99 support!<br> --}}
 
Thanks,<br>
{{ config('app.name') }}<br>
@endcomponent