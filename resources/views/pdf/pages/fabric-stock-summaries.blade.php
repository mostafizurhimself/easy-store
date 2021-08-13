@extends('pdf.layout')

@section('title')
    Fabric Stock Summaries
@endsection

@section('content')
    <h1>Fabric Stock Summaries</h1>
    @if (!empty($subtitle))
        <p>{{ $subtitle }}</p>

    @endif
    <table class="table">


        <tbody>
            <tr>
                <td>SL</td>
                <td>ID</td>
                <td>Location</td>
                <td>Category</td>
                <td>Name</td>
                <td>Previous</td>
                <td>Purchase</td>
                <td>Distribution</td>
                <td>Return</td>
                <td>Transfer</td>
                <td>Receive</td>
                <td>Adjust</td>
                <td>Remaining</td>
            </tr>
            @foreach ($models as $model)
                @php
                    // calculate previous quantity
                    $model->previous_quantity = $model->opening_quantity + $model->previous_purchase_quantity + $model->previous_receive_quantity - ($model->previous_distribution_quantity + $model->previous_return_quantity + $model->previous_transfer_quantity) + $model->previous_adjust_quantity;
                    
                    // calculate remaining quantity
                    $model->remaining_quantity = $model->previous_quantity + $model->purchase_quantity + $model->receive_quantity - ($model->distribution_quantity + $model->return_quantity + $model->transfer_quantity) + $model->adjust_quantity;
                    
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $model->id }}</td>
                    <td>{{ $model->location_name }}</td>
                    <td>{{ $model->category_name }}</td>
                    <td>{{ $model->name }}</td>
                    <td>{{ $model->previous_quantity }}
                        {{ $model->unit_name }}</td>
                    <td>{{ $model->purchase_quantity }} {{ $model->unit_name }}</td>
                    <td>{{ $model->distribution_quantity }} {{ $model->unit_name }}</td>
                    <td>{{ $model->return_quantity }} {{ $model->unit_name }}</td>
                    <td>{{ $model->transfer_quantity }} {{ $model->unit_name }}</td>
                    <td>{{ $model->receive_quantity }} {{ $model->unit_name }}</td>
                    <td>{{ $model->adjust_quantity }} {{ $model->unit_name }}</td>
                    <td>{{ $model->remaining_quantity }} {{ $model->unit_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
