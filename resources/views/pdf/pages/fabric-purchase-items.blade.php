@extends('pdf.layout')

@section('title')
    Fabric Purchase Items
@endsection

@section('content')
    <table class="table">
        <thead>
            <th>Order No</th>
            <th>Fabric</th>
            <th>Quantity</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>Status</th>
        </thead>

        <tbody>
            @foreach ($models as $model)
                <tr>
                    <td>{{$model->purchaseOrder->readableId}}</td>
                    <td>{{$model->fabric->name}}</td>
                    <td>{{$model->purchaseQuantity}}</td>
                    <td>{{$model->purchaseRate}}</td>
                    <td>{{$model->purchaseAmount}}</td>
                    <td>{{$model->status}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
