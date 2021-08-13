@extends('excel.layout')

@section('title')
    Service Receives
@endsection

@section('content')
    <table class="table">
        <tbody>
            <tr>
                <td>#</td>
                <td>Date</td>
                <td>Invoice No</td>
                <td>Reference</td>
                <td>Service</td>
                <td>Quantity</td>
                <td>Rate</td>
                <td>Amount</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $model->date }}</td>
                    <td>{{ $model->invoice->readableId }}</td>
                    <td>{{ $model->reference }}</td>
                    <td>{{ $model->service->name }} ({{ $model->service->code }})</td>
                    <td>{{ $model->quantity }} {{ $model->unit->name }}</td>
                    <td>{{ Helper::currencyShortPdf($model->rate) }}</td>
                    <td>{{ Helper::currencyPdf($model->amount) }}</td>
                    <td>{{ Str::title($model->status) }}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="7">Grand Total</td>
                <td>{{ Helper::currencyPdf($models->sum('amount')) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
