@extends('pdf.layout')

@section('title')
    Service Dispatchs
@endsection

@section('content')
    <h1>Service Dispatchs</h1>
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
                <td>Invoice No</td>
                <td>Service</td>
                <td>Quantity</td>
                <td>Rate</td>
                <td>Amount</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$model->date}}</td>
                    <td>{{$model->invoice->readableId}}</td>
                    <td>{{$model->service->name}} ({{$model->service->code}})</td>
                    <td>{{$model->dispatchQuantity}} {{$model->unitName}}</td>
                    <td>{{Helper::currencyShortPdf($model->dispatchRate)}}</td>
                    <td>{{Helper::currencyPdf($model->dispatchAmount)}}</td>
                    <td>{{Str::title($model->status)}}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="6">Grand Total</td>
                <td>{{Helper::currencyPdf($models->sum('dispatchAmount'))}}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
