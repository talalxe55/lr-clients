@component('mail::message')
<h2>Service {{$service->name}} has been removed for {{$client->name}},</h2>
<div>
    <h2>Details</h2>
    @if($client->name)<p>Client Name: {{$client->name}} </p>@endif
    @if($client->website)<p>Client Website: {{$client->website}} </p>@endif
    @if($client->type)<p>Type: {{$client->ClientType()}} </p>@endif
    @if($service->name)<p>Service Name: {{$service->name}} </p>@endif
    <p>Removed By: {{$user->email}}</p>

</div>
 
{{-- <p>Visit @component('mail::button', ['url' => $body['url_b']])
Laravel Tutorials
@endcomponent and learn more about the Laravel framework.</p> --}}
 
 
{{-- If you do not want to receive these emails then please change your password and report to NLS99 support!<br> --}}
 
Thanks,<br>
{{ config('app.name') }}<br>
@endcomponent