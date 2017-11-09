@component('mail::message')
<p>Beste {{ $user_name }}</p>
<p>Uw wachtwoord is {{ $password }}</p>
@endcomponent