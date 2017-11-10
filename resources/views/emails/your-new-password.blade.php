@component('mail::message')
<p>Beste {{ $user_name }}</p>
<p>Uw wachtwoord is {{ $new_password }}</p>
@endcomponent