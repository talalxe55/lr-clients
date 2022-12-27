@component('mail::message')
<h2>{{$user->email}} has requested to delete client {{$client->name}}</h2>
<div>
    <h2>Client Details</h2>
    @if($client->name)<p>Name: {{$client->name}} </p>@endif
    @if($client->website)<p>Website: {{$client->website}} </p>@endif
    @if($client->type)<p>Type: {{$client->ClientType()}} </p>@endif
    <p>Created at: {{date('d-m-Y H:i:s',strtotime($client->created_at))}} </p>
    <p>Last Updated at: {{date('d-m-Y H:i:s',strtotime($client->updated_at))}} </p>
    <p>Requested By: {{$user->email}}</p>

</div>
 
{{-- <p>Visit @component('mail::button', ['url' => $body['url_b']])
Laravel Tutorials
@endcomponent and learn more about the Laravel framework.</p> --}}
 
 
{{-- If you do not want to receive these emails then please change your password and report to NLS99 support!<br> --}}
 
Thanks,<br>
{{ config('app.name') }}<br>
@endcomponent