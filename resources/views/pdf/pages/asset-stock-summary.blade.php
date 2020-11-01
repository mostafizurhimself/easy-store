@extends('pdf.layout')

@section('title')
    Asset
@endsection

@section('content')
    <h1>Asset Stock Summary</h1>
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
                <td>Total Receive</td>
                <td>Total Distribution</td>
                <td>Total Return</td>
                <td>Total Remaining</td>
            </tr>
            {{-- @foreach ($models as $model)
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
            @endforeach --}}
        </tbody>
    </table>
@endsection
