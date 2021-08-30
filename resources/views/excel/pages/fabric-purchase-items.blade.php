@extends('excel.layout')

@section('title')
    Fabric Purchase Items
@endsection

@section('content')
    <table class="table">
        <tbody>
            <tr>
                <td>#</td>
                <td>Location</td>
                <td>Date</td>
                <td>Fabric</td>
                <td>Order No</td>
                <td>Rate</td>
                <td>Quantity</td>
                <td>Unit</td>
                <td>Amount</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $model->location->name }}</td>
                    <td>{{ $model->date }}</td>
                    <td>{{ $model->fabric->name }} ({{ $model->fabric->code }})</td>
                    <td>{{ $model->purchaseOrder->readableId }}</td>
                    <td>{{ $model->purchaseRate }}</td>
                    <td>{{ $model->purchaseQuantity }} </td>
                    <td>{{ $model->unit->name }} </td>
                    <td>{{ $model->purchaseAmount }}</td>
                    <td>{{ Str::title($model->status) }}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="6">Grand Total</td>
                <td>{{ $models->sum('purchase_quantity') }}</td>
                <td></td>
                <td>{{ $models->sum('purchase_amount') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
