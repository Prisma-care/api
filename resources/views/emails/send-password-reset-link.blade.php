@component('mail::message')
    <p>We have received a password reset request. If you did not make this request, please ignore this email.</p>
    <p><a href="{{ route('reset.check',['token'=>$token]) }}" target="_blank">Please click here to reset your password</a>.</p>
    <p>Werkt de link niet? Plak deze link in je internet browser: {{ route('reset.check',['token'=>$token]) }}</p>
    <p>Meer weten over Prisma?<br>
        www.prisma.care</p>
@endcomponent
