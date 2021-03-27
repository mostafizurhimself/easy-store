@extends('passes.layout')

@section('title')
    Goods
@endsection

@section('content')
<div class="row">
    <div class="col text-center">
        <span class="font-weight-bold">{{$pass->readableId}}</span>
    </div>
</div>
<div class="row mt-3">
    <div class="col">
        <div class="font-weight-bold">Invoice Type</div>
        <div>{{ Helper::getModelResource($pass->invoiceType)::singularLabel() }}</div>
    </div>
    <div class="col text-right">
        <div class="font-weight-bold">Invoice No</div>
        <div>{{$pass->invoice->readableId}}</div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="font-weight-bold">Approved At</div>
        <div>{{$pass->approve->createdAt}}</div>
    </div>
    <div class="col text-right">
        <div class="font-weight-bold">Approved By</div>
        <div>{{$pass->approve->employee->name}}</div>
    </div>
</div>
<table class="table mt-2">
    <thead>
        <th>Name</th>
        <th class="text-right">Quantity</th>
    </thead>
    <tbody>
        <tr>
            <td>Total CTN</td>
            <td class="text-right">{{$pass->details['total_ctn']}}</td>
        </tr>
        <tr>
            <td>Total Poly</td>
            <td class="text-right">{{$pass->details['total_poly']}}</td>
        </tr>
        <tr>
            <td>Total Bag</td>
            <td class="text-right">{{$pass->details['total_bag']}}</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th>Grand Total</th>
            <th class="text-right">{{$pass->details['total_ctn'] + $pass->details['total_poly'] + $pass->details['total_bag']}}</th>
        </tr>
    </tfoot>
</table>

@if ($pass->note)
<div class="row">
    <div class="col approve-time">
        <p style="text-align: justify;"><span class="font-weight-bold mr-2">Note:</span> {{$pass->note}}</p>
    </div>
</div>
@endif
<div class="mt-3 text-center">
    {{QrCode::generate($pass->readableId)}}
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
