@extends('invoices.layout')

@section('title')
    {{$invoice->readableId}}
@endsection


@section('content')

<div id="inventory-invoice">

    <div class="toolbar hidden-print d-print-none">
        <div class="text-right">
            <button id="printInvoice" class="btn btn-light border"><i class="fa fa-print"></i> Print</button>
            <button class="btn btn-light border" id="exportPdf"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
        </div>
        <hr>
    </div>
    <div class="invoice p-2" id="invoice">
        <div class="invoice-container">
            @include('invoices.partials.header')
            <main class="flex-grow-1">
                <div class="row contacts">
                    <div class="col ">
                        <div class="text-gray-light text-uppercase">INVOICE FROM:</div>
                        <h3 class="to">{{$invoice->location->name}}</h3>
                         @if ($invoice->location->locationAddress)
                            <div class="address">
                                @if ($invoice->location->locationAddress->street)
                                    {{$invoice->location->locationAddress->street}}
                                @endif

                                @if ($invoice->location->locationAddress->city)
                                    <span>, </span>{{$invoice->location->locationAddress->city}}
                                @endif

                                @if ($invoice->location->locationAddress->zipcode)
                                    <span>- </span>{{$invoice->location->locationAddress->zipcode}}
                                @endif

                                @if ($invoice->location->locationAddress->country)
                                    <span>, </span>{{$invoice->location->locationAddress->country}}
                                @endif
                            </div>
                        @endif
                        <div class="email"><a href="mailto:{{$invoice->location->email}}">{{$invoice->location->email}}</a></div>
                        <div class="email"><a href="tel:{{$invoice->location->mobile}}">{{$invoice->location->mobile}}</a></div>
                    </div>

                    <div class="col text-right">
                        <div class="text-gray-light text-uppercase">INVOICE TO:</div>
                        <h3 class="from">{{$invoice->receiver->name}}</h3>
                        @if ($invoice->receiver->locationAddress)
                            <div class="address">
                                @if ($invoice->receiver->locationAddress->street)
                                    {{$invoice->receiver->locationAddress->street}}
                                @endif

                                @if ($invoice->receiver->locationAddress->city)
                                    <span>, </span>{{$invoice->receiver->locationAddress->city}}
                                @endif

                                @if ($invoice->receiver->locationAddress->zipcode)
                                    <span>- </span>{{$invoice->receiver->locationAddress->zipcode}}
                                @endif

                                @if ($invoice->receiver->locationAddress->country)
                                    <span>, </span>{{$invoice->receiver->locationAddress->country}}
                                @endif
                            </div>
                        @endif
                        <div class="email"><a href="mailto:{{$invoice->receiver->email}}">{{$invoice->receiver->email}}</a></div>
                        <div class="email"><a href="tel:{{$invoice->receiver->mobile}}">{{$invoice->receiver->mobile}}</a></div>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-12 text-center">
                        <h5 class="text-uppercase">Invoice #<span>{{$invoice->readableId}}</span></h5>
                        <p>Date of Invoice: <span>{{$invoice->date->format('d/m/Y')}}</span></p>
                    </div>
                </div>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th>SR NO.</th>
                            <th class="text-left">DESCRIPTION</th>
                            <th class="text-right">PRICE</th>
                            <th class="text-right">QUANTITY</th>
                            <th class="text-right">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->distributionItems as $item)
                            <tr>
                                <td class="no">{{$loop->iteration}}</td>
                                <td class="text-left"><h3>{{$item->asset->name}}</h3>({{$item->asset->code}})</td>
                                <td class="unit">{{Helper::currencyShort($item->distributionRate)}}</td>
                                <td class="tax">{{$item->distributionQuantity}} {{$item->unit}}</td>
                                <td class="total">{{Helper::currencyShort($item->distributionAmount)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2">GRAND TOTAL</td>
                            <td>{{Helper::currency($invoice->totalDistributionAmount)}}</td>
                        </tr>
                    </tfoot>
                </table>
                <div class="thanks">Thank you!</div>
                <div class="notices">
                    <div>Note:</div>
                    <div class="notice">{{$invoice->note}}</div>
                </div>
            </main>
            @include('invoices.partials.footer')
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script>
        window.onload = function () {
        document.getElementById("exportPdf")
            .addEventListener("click", () => {
                const invoice = this.document.getElementById("invoice");
                var opt = {
                    margin: 0.5,
                    filename: "{{$invoice->readableId}}",
                    image: { type: 'jpg', quality: 1 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                };
                html2pdf().from(invoice).set(opt).save();
            })
    }
    </script>
@endsection
