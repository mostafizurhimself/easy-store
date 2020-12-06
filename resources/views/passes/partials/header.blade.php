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
        </div>
    </div>
</header>
