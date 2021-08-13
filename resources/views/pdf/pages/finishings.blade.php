@extends('pdf.layout')

@section('title')
    Product Finishings
@endsection

@section('content')
    <h1>Product Finishings</h1>
    @if (!empty($subtitle))
        <p>{{ $subtitle }}</p>

    @endif
    <table class="table">


        <tbody>
            <tr>
                <td>#</td>
                <td>Location</td>
                <td>Date</td>
                <td>Invoice</td>
                <td>Product</td>
                <td>Style</td>
                <td>Quantity</td>
                <td>Rate</td>
                <td>Amount</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $model->invoice->location->name }}</td>
                    <td>{{ $model->invoice->date->format('Y-m-d') }}</td>
                    <td>{{ $model->invoice->readableId }}</td>
                    <td>{{ $model->product->name }} ({{ $model->product->code }})</td>
                    <td>{{ $model->style->name }} ({{ $model->style->code }})</td>
                    <td>{{ $model->quantity }} {{ $model->unitName }}</td>
                    <td>{{ Helper::currencyShortPdf($model->rate) }}</td>
                    <td>{{ Helper::currencyPdf($model->amount) }}</td>
                    <td>{{ Str::title($model->status) }}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="6">Grand Total</td>
                <td>{{ $models->sum('quantity') }} {{ $model->unitName }}</td>
                <td></td>
                <td>{{ Helper::currencyPdf($models->sum('amount')) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
