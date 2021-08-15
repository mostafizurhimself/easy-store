@extends('pdf.layout')

@section('title')
    Material Distributions
@endsection

@section('content')
    <h1>Material Purchase Items</h1>
    @if (!empty($subtitle))
        <p>{{ $subtitle }}</p>

    @endif
    <table class="table">
        <tbody>
            <tr>
                <td>#</td>
                <td>Number</td>
                <td>Location</td>
                <td>Distributed At</td>
                <td>Material</td>
                <td>Quantity</td>
                <td>Receiver</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $model->readableId }}</td>
                    <td>{{ $model->location->name }}</td>
                    <td>{{ $model->createdAt->format('Y-m-d') }}</td>
                    <td>{{ $model->material->name }} ({{ $model->material->code }})</td>
                    <td>{{ $model->quantity }} {{ $model->unitName }}</td>
                    <td>{{ $model->receiver->name }}</td>
                    <td>{{ Str::title($model->status) }}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="5">Grand Total</td>
                <td>{{ $models->sum('quantity') }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
