@extends('pdf.layout')

@section('title')
    Employee Gatepass Report
@endsection

@section('content')
    <h1>Employee Gatepass Report</h1>
    @if (!empty($subtitle))
        <p>{{ $subtitle }}</p>
    @endif
    <table class="table">
        <tbody>
            <tr>
                <td>#</td>
                <td>Location</td>
                <td>Pass No</td>
                <td>Employee</td>
                <td>Approved Out</td>
                <td>Out</td>
                <td>Approved In</td>
                <td>In</td>
                <td>Reason</td>
                <td>Approved By</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $model->location->name }}</td>
                    <td>{{ $model->readableId }}</td>
                    <td>{{ $model->employee->name }}</td>
                    <td>{{ $model->approvedOutReadable }}</td>
                    <td>{{ $model->outTimeReadable }}</td>
                    <td>{{ $model->approvedInReadable }}</td>
                    <td>{{ $model->inTimeReadable }}</td>
                    <td>{{ $model->reason }}</td>
                    <td>{{ $model->approverName }}</td>
                    <td>{{ Str::title($model->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
