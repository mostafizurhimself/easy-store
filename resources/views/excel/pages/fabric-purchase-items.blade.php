@extends('excel.layout')

@section('title')
    Fabric Purchase Items
@endsection

@section('content')
    <table class="table">


        <tbody>
            <tr>
                <td>#</td>
                <td>Location</td>
                <td>Date</td>
                <td>Order No</td>
                <td>Fabric</td>
                <td>Quantity</td>
                <td>Rate</td>
                <td>Amount</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$model->location->name}}</td>
                    <td>{{$model->date}}</td>
                    <td>{{$model->purchaseOrder->readableId}}</td>
                    <td>{{$model->fabric->name}} ({{$model->fabric->code}})</td>
                    <td>{{$model->purchaseQuantity}} {{$model->unitName}}</td>
                    <td>{{Helper::currencyShortPdf($model->purchaseRate)}}</td>
                    <td>{{Helper::currencyPdf($model->purchaseAmount)}}</td>
                    <td>{{Str::title($model->status)}}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="7">Grand Total</td>
                <td>{{Helper::currencyPdf($models->sum('purchaseAmount'))}}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
