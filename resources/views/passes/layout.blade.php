<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gate Pass | @yield('title')</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif !important;
        }

        #gate-pass {
            padding: 2mm;
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
            font-weight: bold;
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
                margin: 0mm;
                /* this affects the margin in the printer settings */
            }

        }

    </style>
</head>

<body>
    <div id="gate-pass">

        @include('passes.partials.toolbar')
        <div id="printableArea">
            @include('passes.partials.header')

            <main class="mt-4">
                @yield('content')
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
