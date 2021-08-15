@extends('invoices.layout')

@section('title')
    {{ $invoice->readableId }}
@endsection


@section('content')
    <main class="flex-grow-1">
        <div class="row contacts">
            <div class="col ">
                <div class="text-gray-light text-uppercase">INVOICE FROM:</div>
                <h3 class="to">{{ $invoice->location->name }}</h3>
                @if ($invoice->location->locationAddress)
                    <div class="address">
                        @if ($invoice->location->locationAddress->street)
                            {{ $invoice->location->locationAddress->street }}
                        @endif

                        @if ($invoice->location->locationAddress->city)
                            <span>, </span>{{ $invoice->location->locationAddress->city }}
                        @endif

                        @if ($invoice->location->locationAddress->zipcode)
                            <span>- </span>{{ $invoice->location->locationAddress->zipcode }}
                        @endif

                        @if ($invoice->location->locationAddress->country)
                            <span>, </span>{{ $invoice->location->locationAddress->country }}
                        @endif
                    </div>
                @endif
                <div class="email"><a href="mailto:{{ $invoice->location->email }}">{{ $invoice->location->email }}</a>
                </div>
                <div class="email"><a href="tel:{{ $invoice->location->mobile }}">{{ $invoice->location->mobile }}</a>
                </div>
            </div>

            <div class="col text-right">
                <div class="text-gray-light text-uppercase">INVOICE TO:</div>
                <h3 class="from">{{ $invoice->supplier->name }}</h3>
                @if ($invoice->supplier->locationAddress)
                    <div class="address">
                        @if ($invoice->supplier->locationAddress->street)
                            {{ $invoice->supplier->locationAddress->street }}
                        @endif

                        @if ($invoice->supplier->locationAddress->city)
                            <span>, </span>{{ $invoice->supplier->locationAddress->city }}
                        @endif

                        @if ($invoice->supplier->locationAddress->zipcode)
                            <span>- </span>{{ $invoice->supplier->locationAddress->zipcode }}
                        @endif

                        @if ($invoice->supplier->locationAddress->country)
                            <span>, </span>{{ $invoice->supplier->locationAddress->country }}
                        @endif
                    </div>
                @endif
                <div class="email"><a
                        href="mailto:{{ $invoice->supplier->email }}">{{ $invoice->supplier->email }}</a>
                </div>
                <div class="email"><a href="tel:{{ $invoice->supplier->mobile }}">{{ $invoice->supplier->mobile }}</a>
                </div>
            </div>
        </div>
        <div class="row pt-3">
            <div class="col-12 text-center">
                <h5 class="text-uppercase">Return No: <span>{{ $invoice->readableId }}</span></h5>
                <p>Date of Invoice: <span>{{ $invoice->date->format('d/m/Y') }}</span></p>
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
                @foreach ($invoice->returnItems as $item)
                    <tr>
                        <td class="no">{{ $loop->iteration }}</td>
                        <td class="text-left">
                            <h3>{{ $item->asset->name }}({{ $item->asset->code }})</h3>
                            <span>{!! $item->note !!}</span>
                        </td>
                        <td class="unit">{{ Helper::currencyShort($item->rate) }}</td>
                        <td class="tax">{{ $item->quantity }} {{ $item->unit->name }}</td>
                        <td class="total">{{ Helper::currencyShort($item->amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">GRAND TOTAL</td>
                    <td>{{ $invoice->returnItems->sum('quantity') }}</td>
                    <td>{{ Helper::currency($invoice->totalReturnAmount) }}</td>
                </tr>
            </tfoot>
        </table>
        <div class="thanks">Thank you!</div>
        <div class="row">
            <div class="col-lg-6">
                <div class="notices">
                    <div>Note:</div>
                    <div class="notice">{!! $invoice->note !!}</div>
                </div>
            </div>
            <div class="col-lg-6 d-flex align-items-end justify-content-end">
                <span class="font-weight-bold border-top p-2">
                    Authorize Signature
                </span>
            </div>
        </div>
    </main>
@endsection
