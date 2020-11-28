@extends('pdf.layout')

@section('title')
    Service Transfer Items
@endsection

@section('content')
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
                    <td>{{$model->invoice->date}}</td>
                    <td>{{$model->invoice->readableId}}</td>
                    <td>{{$model->service->name}} ({{$model->service->code}})</td>
                    <td>{{$model->transferQuantity}} {{$model->unit->name}}</td>
                    <td>{{Helper::currencyShortPdf($model->rate)}}</td>
                    <td>{{Helper::currencyPdf($model->transferAmount)}}</td>
                    <td>{{Str::title($model->status)}}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="6">Grand Total</td>
                <td>{{Helper::currencyPdf($models->sum('transfer_amount'))}}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
