@extends('pdf.layout')

@section('title')
    Asset Distribution Items
@endsection

@section('content')
    <table class="table">
        <tbody>
            <tr>
                <td>#</td>
                <td>Location</td>
                <td>Date</td>
                <td>Asset</td>
                <td>Invoice</td>
                <td>Rate</td>
                <td>Quantity</td>
                <td>Amount</td>
                <td>Receiver</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$model->invoice->location->name}}</td>
                    <td>{{$model->invoice->date->format('Y-m-d')}}</td>
                    <td>{{$model->asset->name}} ({{$model->asset->code}})</td>
                    <td>{{$model->invoice->readableId}}</td>
                    <td>{{Helper::currencyShortPdf($model->distributionRate)}}</td>
                    <td>{{$model->distributionQuantity}} {{$model->unitName}}</td>
                    <td>{{Helper::currencyPdf($model->distributionAmount)}}</td>
                    <td>{{$model->invoice->receiver->name}}</td>
                    <td>{{Str::title($model->status)}}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="6">Grand Total</td>
                <td>{{$models->sum('distributionQuantity')}}</td>
                <td>{{Helper::currencyPdf($models->sum('distributionAmount'))}}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
