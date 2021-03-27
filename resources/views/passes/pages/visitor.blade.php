@extends('passes.layout')

@section('title')
    Visitor
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col text-center">
            <span class="font-weight-bold">{{ $pass->readableId }}</span>
        </div>
    </div>

    <table class="table mt-2">
        <tbody>
            <tr>
                <td class="font-weight-bold">Name</td>
                <td class="text-right">{{ $pass->visitorName }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Mobile</td>
                <td class="text-right">{{ $pass->mobile }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">In Time</td>
                <td class="text-right">{{ $pass->in->format('Y-m-d h:i a') }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Card No</td>
                <td class="text-right">{{ $pass->cardNo }}</td>
            </tr>

            @if ($pass->employee()->exists())
                <tr>
                    <td class="font-weight-bold">Visit To</td>
                    <td class="text-right">{{ $pass->employee->name }}</td>
                </tr>
            @endif

            <tr>
                <td class="font-weight-bold">Purpose</td>
                <td class="text-right">{{ $pass->purpose }}</td>
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
                Signature
            </span>
        </div>
    </div>
@endsection
