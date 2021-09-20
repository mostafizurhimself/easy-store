@extends('excel.layout')

@section('title')
    Service Invoices
@endsection

@section('content')
    <table class="table">
        <tbody>
            <tr>
                <td>#</td>
                <td>Location</td>
                <td>Invoice</td>
                <td>Date</td>
                <td>Total Dispatch</td>
                <td>Total Receive</td>
                <td>Total Remaining</td>
                <td>Provider</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $model->location->name }}</td>
                    <td>{{ $model->readableId }}</td>
                    <td>{{ $model->date->format('Y-m-d') }}</td>
                    <td>{{ $model->totalDispatchQuantity }}</td>
                    <td>{{ $model->totalReceiveQuantity }}</td>
                    <td>{{ $model->totalRemainingQuantity }}</td>
                    <td>{{ $model->provider->name }}</td>
                    <td>{{ Str::title($model->status) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total</td>
                <td>{{ $models->sum('totalDispatchQuantity') }}</td>
                <td>{{ $models->sum('totalReceiveQuantity') }}</td>
                <td>{{ $models->sum('totalRemainingQuantity') }}</td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
@endsection
