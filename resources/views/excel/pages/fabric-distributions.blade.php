@extends('excel.layout')

@section('title')
    Fabric Distributions
@endsection

@section('content')
    <table class="table">


        <tbody>
            <tr>
                <td>#</td>
                <td>Location</td>
                <td>Distributed At</td>
                <td>Fabric</td>
                <td>Number</td>
                <td>Rate</td>
                <td>Quantity</td>
                <td>Amount</td>
                <td>Receiver</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $model->location->name }}</td>
                    <td>{{ $model->createdAt->format('Y-m-d h:i A') }}</td>
                    <td>{{ $model->fabric->name }} ({{ $model->fabric->code }})</td>
                    <td>{{ $model->readableId }}</td>
                    <td>{{ Helper::currencyShortPdf($model->rate) }}</td>
                    <td>{{ $model->quantity }} {{ $model->unit->name }}</td>
                    <td>{{ Helper::currencyPdf($model->amount) }}</td>
                    <td>{{ $model->receiver->name }}</td>
                    <td>{{ Str::title($model->status) }}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="6">Grand Total</td>
                <td>{{ $model->sum('quantity') }}</td>
                <td>{{ Helper::currencyPdf($models->sum('amount')) }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
