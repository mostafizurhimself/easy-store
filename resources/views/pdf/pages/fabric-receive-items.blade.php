@extends('pdf.layout')

@section('title')
    Fabric Receive Items
@endsection

@section('content')
    <h1>Fabric Receive Items</h1>
    @if (!empty($subtitle))
        <p>{{$subtitle}}</p>
    @else
        <p>Add your subtitle here</p>
    @endif
    <table class="table">


        <tbody>
            <tr>
                <td>#</td>
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
                    <td>{{$model->date}}</td>
                    <td>{{$model->purchaseOrder->readableId}}</td>
                    <td>{{$model->fabric->name}} ({{$model->fabric->code}})</td>
                    <td>{{$model->quantity}} {{$model->unit}}</td>
                    <td>{{Helper::currencyShortPdf($model->rate)}}</td>
                    <td>{{Helper::currencyPdf($model->amount)}}</td>
                    <td>{{Str::title($model->status)}}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="6">Grand Total</td>
                <td>{{Helper::currencyPdf($models->sum('amount'))}}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
