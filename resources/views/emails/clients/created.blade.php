@component('mail::message')
<h2>New Client {{$client->name}} has been created,</h2>
{{-- <p>Please scan the below QR code or enter the following secret code {{$user->google2fa_secret}} into the Google Authenticator App on your phone  --}}
{{-- @component('mail::button', ['url' => $body['url_a']])
@endcomponent --}}
<div>
    <h2>Client Details</h2>
    @if($client->name)<p>Name: {{$client->name}} </p>@endif
    @if($client->website)<p>Website: {{$client->website}} </p>@endif
    <p>Created at: {{date('d-m-Y H:i:s',strtotime($client->created_at))}} </p>
    <p>Created By: {{$user->email}}</p>
    @if($client->activeServices())
    <h3>Services Added</h3>
    @foreach ($client->activeServices() as $service)
    <p>{{$service->name}}</p>
    @endforeach
    @endif
</div>
</p>
 
{{-- <p>Visit @component('mail::button', ['url' => $body['url_b']])
Laravel Tutorials
@endcomponent and learn more about the Laravel framework.</p> --}}
 
 
{{-- If you do not want to receive these emails then please change your password and report to NLS99 support!<br> --}}
 
Thanks,<br>
{{ config('app.name') }}<br>
@endcomponent