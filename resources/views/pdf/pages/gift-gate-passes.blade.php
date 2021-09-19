@extends('pdf.layout')

@section('title')
    Gift Report
@endsection

@section('content')
    <h1>Gift Report</h1>
    @if (!empty($subtitle))
        <p>{{ $subtitle }}</p>
    @endif
    <table class="table">
        <tbody>
            <tr>
                <td>#</td>
                <td>Location</td>
                <td>Pass No</td>
                <td>Receiver</td>
                <td>Approved By</td>
                <td>Passed At</td>
                <td>Tshirt</td>
                <td>Polo Tshirt</td>
                <td>Shirt</td>
                <td>Gaberdine Pant</td>
                <td>Panjabi</td>
                <td>Pajama</td>
                <td>Kabli</td>
                <td>Total</td>
                <td>Status</td>
            </tr>
            @foreach ($models as $model)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $model->location->name }}</td>
                    <td>{{ $model->readableId }}</td>
                    <td>{{ $model->receiverName }}</td>
                    <td>{{ $model->approverName }}</td>
                    <td>{{ $model->passedAt ? $model->passedAt->format('Y-m-d h:i A') : null }}</td>
                    <td>{{ $model->tshirt }}</td>
                    <td>{{ $model->poloTshirt }}</td>
                    <td>{{ $model->shirt }}</td>
                    <td>{{ $model->gaberdinePant }}</td>
                    <td>{{ $model->panjabi }}</td>
                    <td>{{ $model->pajama }}</td>
                    <td>{{ $model->kabli }}</td>
                    <td>{{ $model->total }}</td>
                    <td>{{ Str::title($model->status) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">Total</td>
                <td>{{ $models->sum('tshirt') }}</td>
                <td>{{ $models->sum('polo_tshirt') }}</td>
                <td>{{ $models->sum('shirt') }}</td>
                <td>{{ $models->sum('gaberdine_pant') }}</td>
                <td>{{ $models->sum('panjabi') }}</td>
                <td>{{ $models->sum('pajama') }}</td>
                <td>{{ $models->sum('kabli') }}</td>
                <td>{{ $models->sum('total') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
@endsection
