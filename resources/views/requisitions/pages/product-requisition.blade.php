@extends('requisitions.layout')

@section('title')
    {{$requisition->readableId}}
@endsection


@section('content')
<main class="flex-grow-1">
    <div class="row contacts">
        <div class="col ">
            <div class="text-gray-light text-uppercase">INVOICE FROM:</div>
            <h3 class="to">{{$requisition->location->name}}</h3>
             @if ($requisition->location->locationAddress)
                <div class="address">
                    @if ($requisition->location->locationAddress->street)
                        {{$requisition->location->locationAddress->street}}
                    @endif

                    @if ($requisition->location->locationAddress->city)
                        <span>, </span>{{$requisition->location->locationAddress->city}}
                    @endif

                    @if ($requisition->location->locationAddress->zipcode)
                        <span>- </span>{{$requisition->location->locationAddress->zipcode}}
                    @endif

                    @if ($requisition->location->locationAddress->country)
                        <span>, </span>{{$requisition->location->locationAddress->country}}
                    @endif
                </div>
            @endif
            <div class="email"><a href="mailto:{{$requisition->location->email}}">{{$requisition->location->email}}</a></div>
            <div class="email"><a href="tel:{{$requisition->location->mobile}}">{{$requisition->location->mobile}}</a></div>
        </div>

        <div class="col text-right">
            <div class="text-gray-light text-uppercase">INVOICE TO:</div>
            <h3 class="from">{{$requisition->receiver->name}}</h3>
            @if ($requisition->receiver->locationAddress)
                <div class="address">
                    @if ($requisition->receiver->locationAddress->street)
                        {{$requisition->receiver->locationAddress->street}}
                    @endif

                    @if ($requisition->receiver->locationAddress->city)
                        <span>, </span>{{$requisition->receiver->locationAddress->city}}
                    @endif

                    @if ($requisition->receiver->locationAddress->zipcode)
                        <span>- </span>{{$requisition->receiver->locationAddress->zipcode}}
                    @endif

                    @if ($requisition->receiver->locationAddress->country)
                        <span>, </span>{{$requisition->receiver->locationAddress->country}}
                    @endif
                </div>
            @endif
            <div class="email"><a href="mailto:{{$requisition->receiver->email}}">{{$requisition->receiver->email}}</a></div>
            <div class="email"><a href="tel:{{$requisition->receiver->mobile}}">{{$requisition->receiver->mobile}}</a></div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-12 text-center">
            <h5 class="text-uppercase">Requisition: <span>{{$requisition->readableId}}</span></h5>
            <p>Date of Invoice: <span>{{$requisition->date->format('d/m/Y')}}</span></p>
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
            @foreach ($requisition->requisitionItems as $item)
                <tr>
                    <td class="no">{{$loop->iteration}}</td>
                    <td class="text-left"><h3>{{$item->product->name}}</h3>({{$item->product->code}})</td>
                    <td class="unit">{{Helper::currencyShort($item->requisitionRate)}}</td>
                    <td class="tax">{{$item->requisitionQuantity}} {{$item->unit->name}}</td>
                    <td class="total">{{Helper::currencyShort($item->requisitionAmount)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"></td>
                <td colspan="2">GRAND TOTAL</td>
                <td>{{Helper::currency($requisition->totalRequisitionAmount)}}</td>
            </tr>
        </tfoot>
    </table>
    <div class="thanks">Thank you!</div>
    <div class="notices">
        <div>Note:</div>
        <div class="notice">{!! $requisition->note !!}</div>
    </div>
</main>
@endsection


