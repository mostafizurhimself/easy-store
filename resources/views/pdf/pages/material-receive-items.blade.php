@extends('pdf.layout')

@section('title')
    Material Receive Items
@endsection

@section('content')
    <h1>Material Receive Items</h1>
    @if (!empty($subtitle))
        <p>{{ $subtitle }}</p>

    @endif
    <table class="table">


        <tbody>
            <tr>
                <td>#</td>
                <td>Location</td>
                <td>Date</td>
                <td>Material</td>
                <td>Order No</td>
                <td>Reference</td>
                <td>Rate</td>
                <td>Quantity</td>
                <td>Amount</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $model->location->name }}</td>
                    <td>{{ $model->date }}</td>
                    <td>{{ $model->material->name }} ({{ $model->material->code }})</td>
                    <td>{{ $model->purchaseOrder->readableId }}</td>
                    <td>{{ $model->reference }}</td>
                    <td>{{ Helper::currencyShortPdf($model->rate) }}</td>
                    <td>{{ $model->quantity }} {{ $model->unitName }}</td>
                    <td>{{ Helper::currencyPdf($model->amount) }}</td>
                    <td>{{ Str::title($model->status) }}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="7">Grand Total</td>
                <td>{{ $models->sum('quantity') }}</td>
                <td>{{ Helper::currencyPdf($models->sum('amount')) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
