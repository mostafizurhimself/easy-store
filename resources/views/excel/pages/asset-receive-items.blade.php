@extends('excel.layout')

@section('title')
    Asset Receive Items
@endsection

@section('content')
    <table class="table">


        <tbody>
            <tr>
                <td>#</td>
                <td>Location</td>
                <td>Date</td>
                <td>Order No</td>
                <td>Asset</td>
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
                    <td>{{$model->asset->name}} ({{$model->asset->code}})</td>
                    <td>{{$model->quantity}} {{$model->unitName}}</td>
                    <td>{{Helper::currencyShortPdf($model->rate)}}</td>
                    <td>{{Helper::currencyPdf($model->amount)}}</td>
                    <td>{{Str::title($model->status)}}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="7">Grand Total</td>
                <td>{{Helper::currencyPdf($models->sum('amount'))}}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
