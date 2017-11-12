@component('mail::message')
<p>Iemand vroeg om je Prisma wachtwoord opnieuw in te stellen. Indien jij dit niet was, dan kan je deze email negeren.</p>
<p><a href="{{ route('reset.check',['token'=>$token]) }}" target="_blank">Stel je wachtwoord opnieuw in</a>.</p>
<p>Werkt de link niet? Plak deze link in je internet browser: {{ route('reset.check',['token'=>$token]) }}</p>
<p>Meer weten over Prisma?<br><a href="https://www.prisma.care">prisma.care</a></p>
@endcomponent