@component('mail::message')
<p>Beste {{ $user_name }}</p>
<p>Je nieuwe Prisma wachtwoord is <em>{{ $password }}</em>.</p>
<p>Geniet van leuke momenten met je geliefden.</p>
<p>Meer info over Prisma vind je op www.prisma.care</p>
@endcomponent