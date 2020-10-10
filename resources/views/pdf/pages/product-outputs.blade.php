@extends('pdf.layout')

@section('title')
    Product Outputs
@endsection

@section('content')
    <h1>Product Outputs</h1>
    @if (!empty($subtitle))
        <p>{{$subtitle}}</p>
    @else
        <p>Add your subtitle here</p>
    @endif
    <table class="table">


        <tbody>
            <tr>
                <td>#</td>
                <td>Date</td>
                <td>Location</td>
                <td>Category</td>
                <td>Style</td>
                <td>Quantity</td>
                <td>Rate</td>
                <td>Amount</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$model->date->format('Y-m-d')}}</td>
                    <td>{{$model->location->name}}</td>
                    <td>{{$model->category->name}}</td>
                    <td>{{$model->style->name}} ({{$model->style->code}})</td>
                    <td>{{$model->quantity}} {{$model->unitName}}</td>
                    <td>{{Helper::currencyShortPdf($model->rate)}}</td>
                    <td>{{Helper::currencyPdf($model->amount)}}</td>
                    <td>{{Str::title($model->status)}}</td>
                </tr>
            @endforeach
            <tr class="tfoot">
                <td colspan="5">Grand Total</td>
                <td>{{$models->sum('quantity')}} {{$model->unitName}}</td>
                <td></td>
                <td>{{Helper::currencyPdf($models->sum('amount'))}}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
