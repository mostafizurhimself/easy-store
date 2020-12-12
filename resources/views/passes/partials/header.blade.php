<header>
    <div class="header">
        <div class="logo text-center">
            <a target="_blank" href="#">
                @if(Settings::companyLogo())
                    <img src="{{Settings::companyLogo()}}" alt="Logo" height="60px">
                @else
                    <h1>COMPANY LOGO</h1>
                @endif
            </a>
        </div>
        <div class="company text-center mt-2">
            <h6 class="name mb-0">
                @if(empty(Settings::company()->name))
                    <a target="_blank" href="#">Company Name</a>
                @else
                    <a target="_blank" href="#">{{Settings::company()->name}}</a>
                @endif
            </h6>

            {{-- Company Address --}}
            {{-- @if(empty($pass->location->address))
                <div>Address goes here</div>
            @else
                <div >{{$pass->location->address}}</div>
            @endif --}}

            @if ($pass->location->locationAddress)
                <div class="address">
                    @if ($pass->location->locationAddress->street)
                        {{$pass->location->locationAddress->street}}
                    @endif

                    @if ($pass->location->locationAddress->city)
                        <span>, </span>{{$pass->location->locationAddress->city}}
                    @endif

                    @if ($pass->location->locationAddress->zipcode)
                        <span>- </span>{{$pass->location->locationAddress->zipcode}}
                    @endif

                    @if ($pass->location->locationAddress->country)
                        <span>, </span>{{$pass->location->locationAddress->country}}
                    @endif
                </div>
            @endif

            {{-- Company Mobile --}}
            @if(empty($pass->location->mobile))
                <div>(123) 456-789</div>
            @else
                <div >{{$pass->location->mobile}}</div>
            @endif
        </div>
    </div>
</header>
