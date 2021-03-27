@extends('passes.layout')

@section('title')
    Employee
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col text-center">
            <span class="font-weight-bold">{{ $pass->readableId }}</span>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col">
            <div class="font-weight-bold">Approved At</div>
            <div>{{ $pass->approve->createdAt->format('Y-m-d h:i a') }}</div>
        </div>
        <div class="col text-right">
            <div class="font-weight-bold">Approved By</div>
            <div>{{ $pass->approve->employee->name }}</div>
        </div>
    </div>
    <table class="table mt-2 table-bordered">
        <tbody>
            <tr>
                <td class="font-weight-bold">Employee</td>
                <td class="text-right">{{ $pass->employee->name }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Approved Out</td>
                <td class="text-right">{{ $pass->approveOut ? $pass->approvedOut->format('Y-m-d h:i a') : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Approved In</td>
                <td class="text-right">{{ $pass->approveIn ? $pass->approvedIn->format('Y-m-d h:i a') : 'N/A' }}</td>
            </tr>

            <tr>
                <td class="font-weight-bold">Reason</td>
                <td class="text-right">{{ $pass->reason }}</td>
            </tr>

            <tr>
                <td class="font-weight-bold">Early Leave</td>
                <td class="text-right">
                    @if ($pass->earlyLeave)
                        Yes
                    @else
                        No
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <div class="mt-3 text-center">
        {{ QrCode::generate($pass->readableId) }}
    </div>

    <div class="row mt-6 align-items-center">
        <div class="col">
            <div class="font-weight-bold">Thank you!</div>
        </div>
        <div class="col text-right">
            <span class="font-weight-bold border-top p-2">
                Authorize Signature
            </span>
        </div>
    </div>
@endsection
