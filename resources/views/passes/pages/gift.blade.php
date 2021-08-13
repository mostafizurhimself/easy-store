@extends('passes.layout')

@section('title')
    Gift
@endsection

@section('content')
    <div class="row">
        <div class="col text-center">
            <span class="font-weight-bold">{{ $pass->readableId }}</span>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col">
            <div class="font-weight-bold">Approved At</div>
            <div>{{ $pass->approve->createdAt }}</div>
        </div>
        <div class="col text-right">
            <div class="font-weight-bold">Approved By</div>
            <div>{{ $pass->approve->employee->name }}</div>
        </div>
    </div>
    <table class="table mt-2">
        <thead>
            <th>Name</th>
            <th class="text-right">Quantity</th>
        </thead>
        <tbody>
            @if ($pass->tshirt)
                <tr>
                    <th>Tshirt</th>
                    <th class="text-right">{{ $pass->tshirt }}</th>
                </tr>
            @endif

            @if ($pass->poloTshirt)
                <tr>
                    <th>Polo Tshirt</th>
                    <th class="text-right">{{ $pass->poloTshirt }}</th>
                </tr>
            @endif

            @if ($pass->shirt)
                <tr>
                    <th>Shirt</th>
                    <th class="text-right">{{ $pass->shirt }}</th>
                </tr>
            @endif

            @if ($pass->gaberdinePant)
                <tr>
                    <th>Gaberdine Pant</th>
                    <th class="text-right">{{ $pass->gaberdinePant }}</th>
                </tr>
            @endif

            @if ($pass->panjabi)
                <tr>
                    <th>Panjabi</th>
                    <th class="text-right">{{ $pass->panjabi }}</th>
                </tr>
            @endif

            @if ($pass->pajama)
                <tr>
                    <th>Pajama</th>
                    <th class="text-right">{{ $pass->pajama }}</th>
                </tr>
            @endif

            @if ($pass->kabli)
                <tr>
                    <th>Kabli</th>
                    <th class="text-right">{{ $pass->kabli }}</th>
                </tr>
            @endif

        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th class="text-right">{{ $pass->total }}</th>
            </tr>
        </tfoot>
    </table>


    @if ($pass->note)
        <div class="row">
            <div class="col approve-time">
                <p style="text-align: justify;"><span class="font-weight-bold mr-2">Note:</span> {{ $pass->note }}</p>
            </div>
        </div>
    @endif
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
