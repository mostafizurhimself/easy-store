<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Report</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif !important;
            font-weight: 600;
        }

        #report {
            margin: 0 auto;
            width: 80mm;
            background: #FFF;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
        }

        .header {
            text-align: center
        }

        .name a {
            text-decoration: none;
            font-size: 1rem;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-weight-bold {
            font-weight: 800;
        }

        .row {
            display: flex;
        }

        .col {
            flex-grow: 1;
        }

        .mt-2 {
            margin-top: 0.825rem;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .mt-5 {
            margin-top: 2rem;
        }

        .mt-6 {
            margin-top: 3rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td,
        tr,
        tbody,
        thead {
            padding: 0.5rem;
            border: 1px solid black;
        }

        .toolbar {
            display: flex;
            margin-bottom: 2rem;
            justify-content: space-around;
        }

        .toolbar .btn {
            padding: 0.5rem 3rem;
            border: none;
            font-family: inherit;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-primary {
            background: #1dbf73;
            color: white;
        }

        @media print {
            * {
                font-size: 12px;
                line-height: 20px;
            }

            body {
                font-family: 'Nunito', sans-serif !important;
            }

            @page {
                size: auto;
                /* auto is the initial value */
                margin: 2mm;
                padding: 0;
                /* this affects the margin in the printer settings */
            }

        }

    </style>
</head>

<body>
    <div id="report" style="margin-bottom: 50px;">

        @include('passes.partials.toolbar')
        <div id="printableArea">
            <header>
                <div class="header">
                    <div class="text-center">
                        <a target="_blank" href="#">
                            @if (Settings::companyLogo())
                                <img src="{{ Settings::companyLogo() }}" alt="Logo" height="60px">
                            @else
                                <h1>COMPANY LOGO</h1>
                            @endif
                        </a>
                    </div>
                    <div class="text-center">
                        <h6 class="name">
                            @if (empty(Settings::company()->name))
                                <a target="_blank" href="#">Company Name</a>
                            @else
                                <a target="_blank" href="#">{{ Settings::company()->name }}</a>
                            @endif
                        </h6>
                    </div>

                    @if ($location->locationAddress)
                        <div class="address">
                            @if ($location->locationAddress->street)
                                {{ $location->locationAddress->street }}
                            @endif

                            @if ($location->locationAddress->city)
                                <span>, </span>{{ $location->locationAddress->city }}
                            @endif

                            @if ($location->locationAddress->zipcode)
                                <span>- </span>{{ $location->locationAddress->zipcode }}
                            @endif

                            @if ($location->locationAddress->country)
                                <span>, </span>{{ $location->locationAddress->country }}
                            @endif
                        </div>
                    @endif

                    {{-- Company Mobile --}}
                    @if (empty($location->mobile))
                        <div>(123) 456-789</div>
                    @else
                        <div>{{ $location->mobile }}</div>
                    @endif
                </div>
            </header>


            <main class="mt-4">
                <div class="row mb-3">
                    <div class="col text-center">
                        <span class="font-weight-bold">Date:</span>
                        <span class="font-weight-bold">{{ $date }}</span>
                    </div>
                </div>

                <table class="table mt-2">
                    <thead style="background: whitesmoke">
                        <tr>
                            <th>Department</th>
                            <th>Attendance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($result as $row)
                            <tr>
                                <td>{{ $row->department }}</td>
                                <td align="right">{{ $row->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="background: whitesmoke">
                        <tr>
                            <td class="font-weight-bold">Total Employee</td>
                            <td align="right">{{ $totalEmployee }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Total Present</td>
                            <td align="right">{{ $totalPresent }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Total Absent</td>
                            <td align="right">{{ $totalAbsent }}</td>
                        </tr>
                    </tfoot>
                </table>

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
            </main>
        </div>
    </div>

    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
        window.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                printDiv('printableArea')
            }
        })

    </script>
</body>

</html>
