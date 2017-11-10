@component('mail::message')
<p>Beste {{ $user_name }}</p>
<p>Bedankt om deel uit te maken van Prisma. Je wachtwoord is {{ $password }}</p>
@endcomponent