@extends('pdf.layout')

@section('title')
    Employee List
@endsection

@section('content')
    <h1>Employee List</h1>
    @if (!empty($subtitle))
        <p>{{ $subtitle }}</p>
    @endif
    <table class="table">
        <tbody>
            <tr>
                <td>#</td>
                <td>Location</td>
                <td>Name</td>
                <td>Employee Id</td>
                <td width="200px">Mobile</td>
                <td>Blood</td>
                <td>Department</td>
                <td>Section</td>
                <td>Designation</td>
                <td>Salary</td>
                <td>Joining Date</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $model->location->name }}</td>
                    <td>{{ $model->name }}</td>
                    <td>{{ $model->readableId }}</td>
                    <td>{{ $model->mobile }}</td>
                    <td>{{ $model->bloodGroup }}</td>
                    <td>{{ $model->departmentName }}</td>
                    <td>{{ $model->sectionName }}</td>
                    <td>{{ $model->designationName }}</td>
                    <td>{{ $model->salary }}</td>
                    <td>{{ $model->joiningDate->format('Y-m-d') }}</td>
                    <td>{{ Str::title($model->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
