@extends('excel.layout')

@section('title')
    Material Receive Items
@endsection

@section('content')
    <table class="table">


        <tbody>
            <tr>
                <td>#</td>
                <td>Location</td>
                <td>Date</td>
                <td>Order No</td>
                <td>Material</td>
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
                    <td>{{$model->material->name}} ({{$model->material->code}})</td>
                    <td>{{$model->quantity}} {{$model->unitName}}</td>
                    <td>{{Helper::currencyShortPdf($model->rate)}}</td>
                    <td>{{Helper::currencyPdf($model->amount)}}</td>
                    <td>{{Str::title($model->status)}}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="8">Grand Total</td>
                <td>{{Helper::currencyPdf($models->sum('amount'))}}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
