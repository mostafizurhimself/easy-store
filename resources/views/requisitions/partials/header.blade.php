<header>
    <div class="row">
        <div class="col">
            <a target="_blank" href="#">
                @if(Settings::companyLogo())
                    <img src="{{Settings::companyLogo()}}" alt="Logo" height="100px">
                @else
                    <h1>COMPANY LOGO</h1>
                @endif
            </a>
        </div>
        <div class="col company-details">
            <h2 class="name">
                @if(empty(Settings::company()->name))
                    <a target="_blank" href="#">Company Name</a>
                @else
                    <a target="_blank" href="#">{{Settings::company()->name}}</a>
                @endif
            </h2>

            {{-- Company Address --}}
            @if(empty(Settings::company()->address))
                <div>Address goes here</div>
            @else
                <div >{{Settings::company()->address}}</div>
            @endif

            {{-- Company Mobile --}}
            @if(empty(Settings::company()->mobile))
                <div>(123) 456-789</div>
            @else
                <div >{{Settings::company()->mobile}}</div>
            @endif

            {{-- Company Email --}}
            @if(empty(Settings::company()->email))
                <div>(123) 456-789</div>
            @else
                <div >{{Settings::company()->email}}</div>
            @endif
        </div>
    </div>
</header>
