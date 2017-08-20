@component('mail::message')
<p>Dear {{ $user_name }}</p>
<p>Thank you for joining Prisma. Your password is {{ $password }}</p>
@endcomponent
