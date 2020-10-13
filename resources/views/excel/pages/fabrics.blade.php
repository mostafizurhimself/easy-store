@extends('pdf.layout')

@section('title')
    Fabrics Report
@endsection

@section('content')
    <table class="table">
        <tbody>
            <tr>
                <td>#</td>
                <td>Location</td>
                <td>Name</td>
                <td>Code</td>
                <td>Rate</td>
                <td>Category</td>
                <td>Quantity</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$model->location->name}}</td>
                    <td>{{$model->name}}</td>
                    <td>{{$model->code}}</td>
                    <td>{{$model->rate}}</td>
                    <td>{{$model->category ? $model->category->name : "N/A"}}</td>
                    <td>{{$model->quantity}} {{$model->unit->name}}</td>
                    <td>{{Str::title($model->status)}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
