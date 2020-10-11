@extends('invoices.layout')

@section('title')
    {{$invoice->readableId}}
@endsection


@section('content')
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
            <h3 class="from">{{$invoice->provider->name}}</h3>
            @if ($invoice->provider->locationAddress)
                <div class="address">
                    @if ($invoice->provider->locationAddress->street)
                        {{$invoice->provider->locationAddress->street}}
                    @endif

                    @if ($invoice->provider->locationAddress->city)
                        <span>, </span>{{$invoice->provider->locationAddress->city}}
                    @endif

                    @if ($invoice->provider->locationAddress->zipcode)
                        <span>- </span>{{$invoice->provider->locationAddress->zipcode}}
                    @endif

                    @if ($invoice->provider->locationAddress->country)
                        <span>, </span>{{$invoice->provider->locationAddress->country}}
                    @endif
                </div>
            @endif
            <div class="email"><a href="mailto:{{$invoice->provider->email}}">{{$invoice->provider->email}}</a></div>
            <div class="email"><a href="tel:{{$invoice->provider->mobile}}">{{$invoice->provider->mobile}}</a></div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-12 text-center">
            <h5 class="text-uppercase">Invoice: <span>{{$invoice->readableId}}</span></h5>
            <p>Date of Invoice: <span>{{$invoice->date->format('d/m/Y')}}</span></p>
        </div>
    </div>
    <table border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>SR NO.</th>
                <th class="text-left">DESCRIPTION</th>
                <th class="text-right">RATE</th>
                <th class="text-right">QUANTITY</th>
                {{-- <th class="text-right">TOTAL</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->dispatches as $item)
                <tr>
                    <td class="no">{{$loop->iteration}}</td>
                    <td class="text-left">
                        <h3>{{$item->service->name}} ({{$item->service->code}})</h3>
                        <span>{!! $item->description !!}</span>
                    </td>
                    {{-- <td class="unit">{{Helper::currencyShort($item->rate)}}</td> --}}
                    <td class="tax">{{$item->dispatchQuantity}} {{$item->unit->name}}</td>
                    {{-- <td class="total">{{Helper::currencyShort($item->dispatchAmount)}}</td> --}}
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td>GRAND TOTAL</td>
                <td>{{$invoice->dispatches()->sum('dispatch_quantity')}} {{$invoice->dispatches()->first()->unit->name}}</td>
            </tr>
        </tfoot>
    </table>
    <div class="thanks">Thank you!</div>
    <div class="notices">
        <div>Note:</div>
        <div class="notice">{!! $invoice->description !!}</div>
    </div>
</main>

@endsection

