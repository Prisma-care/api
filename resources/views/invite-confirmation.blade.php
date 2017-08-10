@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Installeer de Prisma app</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <p>Je hebt nu een account voor de Prisma app.</p>

                        <a href="#" class="btn btn-primary">Installeer de app</a> en meldt je aan.

                        <p>Na het installeren en openen van de app dien je je e-mail adres en wachtwoord nog in te
                            geven. Hou deze goed bij.</p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
