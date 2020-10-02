<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Purchase Order | @yield('title')</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
    </head>
    <body>
        <div id="inventory-invoice">

            {{-- Toolbar --}}
            @include('purchase-orders.partials.toolbar')

            {{-- Invoice main --}}
            <div class="invoice p-2" id="invoice">
                <div class="invoice-container">

                    {{-- Invoice header --}}
                    @include('purchase-orders.partials.header')

                    {{-- Invoice Body --}}
                    <main class="flex-grow-1">

                        {{-- Invoice contacts --}}
                        @include('purchase-orders.partials.contacts')

                        {{-- Invoice items --}}
                        @yield('content')

                        {{-- Invoice Footer --}}
                        @include('purchase-orders.partials.footer')

                    </main>
                </div>
            </div>

        </div>

        {{-- Scripts --}}
        <script src="{{asset('js/app.js')}}"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
        <script>
            window.onload = function () {
            document.getElementById("exportPdf")
                .addEventListener("click", () => {
                    const invoice = this.document.getElementById("invoice");
                    var opt = {
                        margin: 0.5,
                        filename: "{{$purchaseOrder->readableId}}",
                        image: { type: 'jpg', quality: 1 },
                        html2canvas: { scale: 2 },
                        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                    };
                    html2pdf().from(invoice).set(opt).save();
                })
            }
        </script>

        @yield('scripts')

    </body>
</html>
