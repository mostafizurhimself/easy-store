<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Favicon -->
    <link rel="icon" href="{{Settings::companyLogo() ?? asset('images/logo.jpg')}}" type="image/gif" sizes="16x16">
    <title>@yield('title')</title>
    @include('invoices.partials.style')

</head>
<body>
    <div id="invoice">
        <div class="invoice">
            <div style="min-width: 600px">

                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
