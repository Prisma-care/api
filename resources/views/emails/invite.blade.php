@component('mail::message')
    @component('mail::panel')
<p>Geef mee kleur aan de herinneringen van {{ $patient }} met de Prisma app voor Android smartphones en tablets.</p>
<p><a href="{{ route('password.set',['token'=>$token]) }}" class="button button-blue" target="_blank">Bevestig je e-mail adres via deze link</a> en kies daarna een wachtwoord.</p>
<p>Werkt de link niet? Plak deze link in je internet browser: {{ route('password.set',['token'=>$token]) }}</p>
<p>Meer weten over Prisma?<br>
    www.prisma.care</p>
    @endcomponent
@endcomponent
