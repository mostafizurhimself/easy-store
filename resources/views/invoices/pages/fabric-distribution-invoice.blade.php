@extends('invoices.layout')

@section('title')
    {{$invoice->readableId}}
@endsection


@section('content')
<main class="flex-grow-1">
    <div class="row contacts">
        <div class="col text-left">
            <div class="text-gray-light text-uppercase">Receiver:</div>
            <h3 class="from">{{$invoice->receiver->name}}</h3>
            <div class="font-weight-bold">{{$invoice->receiver->designation->name}}</div>
            <div class="email"><a href="mailto:{{$invoice->receiver->email}}">{{$invoice->receiver->email}}</a></div>
            <div class="email"><a href="tel:{{$invoice->receiver->mobile}}">{{$invoice->receiver->mobile}}</a></div>
        </div>

        <div class="col text-right">
            <h5 class="text-uppercase">Invice No: <span>{{$invoice->readableId}}</span></h5>
            <p>Date of Invoice: <span>{{$invoice->createdAt->format('d/m/Y')}}</span></p>
        </div>
    </div>
    <table border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th class="text-left">DESCRIPTION</th>
                <th class="text-left">CODE</th>
                <th class="text-right">QUANTITY</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-left">
                    <h3>{{$invoice->fabric->name}}</h3>
                </td>
                <td><h3>{{$invoice->fabric->code}}</h3></td>
                <td class="tax">{{$invoice->quantity}} {{$invoice->unit->name}}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="font-weight-bold">GRAND TOTAL</td>
                <td class="font-weight-bold">{{$invoice->quantity}} {{$invoice->unit->name}}</td>
            </tr>
        </tfoot>
    </table>
    <div class="row">
        <div class="col-6">
            <div class="thanks">Thank you!</div>
            <div class="notices">
                <div class="font-weight-bold">Note:</div>
                <div class="notice">{!! $invoice->description !!}</div>
            </div>
        </div>
        <div class="col-6 d-flex align-items-end justify-content-end">
            <span class="font-weight-bold border-top p-2">Authorize Signature</span>
        </div>
    </div>
</main>
@endsection


