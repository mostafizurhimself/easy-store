<div class="row mb-3">
    <div class="col-6">
        <div class="font-weight-bold"><u>Terms and Conditions:</u></div>
        <div>{!! $purchaseOrder->note !!}</div>
    </div>
    <div class="col-6 text-right">
        <div class="font-weight-bold">Authorized By</div>
        @if($purchaseOrder->approve)
        <div class="font-weight-bold mt-5">{{$purchaseOrder->approver->name}}</div>
        <div class="">{{$purchaseOrder->approver->designation->name}}</div>
        <div class="">{{$purchaseOrder->approver->mobile}}</div>
        <div class="">{{$purchaseOrder->approver->email}}</div>
        @endif
    </div>
</div>
