@component('mail::message')

    @component('mail::panel')

        Geef mee kleur aan de herinneringen van Marie-Josée met de Prisma app voor Android smartphones en tablets.

        @component('mail::button', ['url' => $url, 'color' => 'blue'])
            Bevestig je e-mail adres via deze link
        @endcomponent

        en kies daarna een wachtwoord.

        Werkt de link niet? Plak deze link in je internet browser: $url

        Meer weten over Prisma?
        www.prisma.care

    @endcomponent

@endcomponent