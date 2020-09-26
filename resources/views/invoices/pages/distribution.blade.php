@extends('invoices.invoice')

@section('title')
    Distribution Invoice
@endsection


@section('content')

{{-- Header --}}
@include('invoices.partials.header', ['invoice' => $invoice])

{{-- Content --}}
<main>
    <div class="row contacts">
        <div class="col invoice-to">
            <div class="text-gray-light">INVOICE TO:</div>
            <h2 class="to">{{$invoice->receiver->name}}</h2>
            @if($invoice->receiver->locationAddress)
            <div class="address">{{$invoice->receiver->locationAddress->street}}, {{$invoice->receiver->locationAddress->city}} - {{$invoice->receiver->locationAddress->zipcode}}</div>
            @endif
            <div class="email"><a href="tel:john@example.com">{{$invoice->receiver->mobile}}</a></div>
            <div class="email"><a href="mailto:{{$invoice->receiver->email}}">{{$invoice->receiver->email}}</a></div>
        </div>
        <div class="col invoice-details">
            <h2 class="invoice-id">INVOICE #{{$invoice->readableId}}</h2>
            <div class="date">Date of Invoice: {{$invoice->date->format('d/m/Y')}}</div>
            <div class="date">Reference: {{$invoice->reference}}</div>
        </div>
    </div>
    <table border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>#</th>
                <th class="text-left">DESCRIPTION</th>
                <th class="text-right">RATE</th>
                <th class="text-right">QUANTITY</th>
                <th class="text-right">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->distributionItems as $item)
                <tr>
                    <td class="no">{{$loop->iteration}}</td>
                    <td class="text-left">
                        <h3>
                            {{$item->asset->name}}
                        </h3>
                        <span>({{$item->asset->code}})</span>
                    </td>
                    <td class="unit">{{Helper::currencyShort($item->distributionRate)}}</td>
                    <td class="qty">{{$item->distributionQuantity}}</td>
                    <td class="total">{{Helper::currencyShort($item->distributionAmount)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>

            <tr>
                <td colspan="2"></td>
                <td colspan="2"><h4>TOTAL:</h4></td>
                <td>{{Helper::currency($invoice->totalDistributionAmount)}}</td>
            </tr>
        </tfoot>
    </table>
</main>




@endsection

