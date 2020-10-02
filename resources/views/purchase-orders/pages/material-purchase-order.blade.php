@extends('purchase-orders.layout')

@section('title')
    {{$purchaseOrder->readableId}}
@endsection


@section('content')

<table border="0" cellspacing="0" cellpadding="0" class="mb-5">
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
        @foreach ($purchaseOrder->purchaseItems as $item)
            <tr>
                <td class="no">{{$loop->iteration}}</td>
                <td class="text-left"><h3>{{$item->material->name}}</h3>({{$item->material->code}})</td>
                <td class="unit">{{Helper::currencyShort($item->purchaseRate)}}</td>
                <td class="tax">{{$item->purchaseQuantity}} {{$item->unit}}</td>
                <td class="total">{{Helper::currencyShort($item->purchaseAmount)}}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"></td>
            <td colspan="2">GRAND TOTAL</td>
            <td>{{Helper::currency($purchaseOrder->totalPurchaseAmount)}}</td>
        </tr>
    </tfoot>
</table>
@endsection
