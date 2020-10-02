<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Requisition | @yield('title')</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
    </head>
    <body>
        <div id="inventory-invoice">

            {{-- Toolbar --}}
            @include('requisitions.partials.toolbar')
            <div class="invoice p-2" id="invoice">

                <div class="invoice-container">

                    {{-- Invoice Header --}}
                    @include('requisitions.partials.header')

                    {{-- Invoice Content --}}
                    @yield('content')

                    {{-- Invoice Footer --}}
                    @include('requisitions.partials.footer')
                </div>
            </div>
        </div>

        @include('requisitions.partials.scripts')

        @yield('scripts')

    </body>
</html>
