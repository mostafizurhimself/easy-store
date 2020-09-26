<header>
    <div class="row">
        <div class="col">
            <a class="logo" href="#">
                <img src="{{Settings::companyLogo() ?? asset('images/logo.jpg')}}" data-holder-rendered="true" />
            </a>
        </div>
        <div class="col company-details">
            <h2 class="name">
                <a target="_blank" href="#">
                    {{$invoice->location->name}}
                </a>
            </h2>
            @if($invoice->location->locationAddress)
        <div>{{$invoice->location->locationAddress->street}}, {{$invoice->location->locationAddress->city}} - {{$invoice->location->locationAddress->zipcode}}</div>
            @endif
            <div>{{$invoice->location->mobile}}</div>
            <div>{{$invoice->location->email}}</div>
        </div>
    </div>
</header>
