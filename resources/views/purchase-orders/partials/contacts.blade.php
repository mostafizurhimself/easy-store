<div class="row contacts">

    <div class="col text-left">
        <div class="text-gray-light text-uppercase">TO,</div>
        <h3 class="from">{{$purchaseOrder->supplier->name}}</h3>
        @if ($purchaseOrder->supplier->locationAddress)
            <div class="address">
                @if ($purchaseOrder->supplier->locationAddress->street)
                    {{$purchaseOrder->supplier->locationAddress->street}}
                @endif

                @if ($purchaseOrder->supplier->locationAddress->city)
                    <span>, </span>{{$purchaseOrder->supplier->locationAddress->city}}
                @endif

                @if ($purchaseOrder->supplier->locationAddress->zipcode)
                    <span>- </span>{{$purchaseOrder->supplier->locationAddress->zipcode}}
                @endif

                @if ($purchaseOrder->supplier->locationAddress->country)
                    <span>, </span>{{$purchaseOrder->supplier->locationAddress->country}}
                @endif
            </div>
        @endif
        <div class="email"><a href="mailto:{{$purchaseOrder->supplier->email}}">{{$purchaseOrder->supplier->email}}</a></div>
        <div class="email"><a href="tel:{{$purchaseOrder->supplier->mobile}}">{{$purchaseOrder->supplier->mobile}}</a></div>
    </div>

    <div class="col text-right">
        <h3 class="text-uppercase">Order No</h3>
        <div class="font-weight-bold">#{{$purchaseOrder->readableId}}</div>
        <div>Date: <span>{{$purchaseOrder->date->format('d/m/Y')}}</span></div>
    </div>
</div>
<div class="row mb-3">
    <div class="col">

        @if ($purchaseOrder->supplier->contactPerson)
            <div class="text-gray-light text-uppercase">Ship To:</div>
            <h4 class="font-weight-bold">{{$purchaseOrder->location->name}}</h4>
            @if ($purchaseOrder->location->locationAddress)
                <div class="address">
                    @if ($purchaseOrder->location->locationAddress->street)
                        {{$purchaseOrder->location->locationAddress->street}}
                    @endif

                    @if ($purchaseOrder->location->locationAddress->city)
                        <span>, </span>{{$purchaseOrder->location->locationAddress->city}}
                    @endif

                    @if ($purchaseOrder->location->locationAddress->zipcode)
                        <span>- </span>{{$purchaseOrder->location->locationAddress->zipcode}}
                    @endif

                    @if ($purchaseOrder->location->locationAddress->country)
                        <span>, </span>{{$purchaseOrder->location->locationAddress->country}}
                    @endif
                </div>
                <div class="email"><a href="mailto:{{$purchaseOrder->location->email}}">{{$purchaseOrder->location->email}}</a></div>
                <div class="email"><a href="tel:{{$purchaseOrder->location->mobile}}">{{$purchaseOrder->location->mobile}}</a></div>
            @endif
        @endif
    </div>
</div>
