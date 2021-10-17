<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee IDs</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif !important;
            font-size: 11px;
        }

        .container {
            max-width: 700px;
            padding: 2rem;
            margin: auto;
            background: white;
        }

        #printableArea {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .id-card {
            display: flex;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .id-card:nth-child(5n) {
            page-break-after: always;
        }

        .id-card__left {
            margin-right: 10px;
        }

        .id-card__left,
        .id-card__right {
            height: 185px;
            width: 320px;
            border: 1px solid black;
            display: flex;
            flex-direction: column;

        }

        .id-card__header {
            height: 20px;
            /* background: #273983; */
            background: #129457;
            text-transform: uppercase;
            font-weight: bold;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 10px;
            justify-content: space-between;
        }

        .id-card__header p {
            white-space: nowrap;
        }

        .id-card__body {
            flex-grow: 1;
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }

        .left-section {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .info-wrapper::before {
            content: '';
            display: block;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0.2;
            background-image: url('{{ asset('images/easy-logo.jpg') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 120px;
        }

        .right-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .employee-photo img {
            height: 100px;
            width: 100px;
            border: 1px solid black;
        }

        .id-card__footer {
            height: 10px;
            background: #129457;
        }

        .id-card__body--back {
            flex-grow: 1;
            display: flex;
            padding: 10px;
            align-items: center;
            justify-content: space-between;
        }

        .toolbar {
            display: flex;
            margin-bottom: 10px;
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

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-uppercase {
            text-transform: uppercase
        }

        .text-red {
            color: red;
        }

        .text-green {
            color: #1dbf73;
        }

        .font-bold {
            font-weight: bold !important;
        }

        .font-bolder {
            font-weight: 800 !important;
        }

        .btn-primary {
            background: #129457;
            color: white;
        }

        .no-wrap {
            white-space: nowrap !important;
        }

        .relative {
            position: relative;
        }

        .d-flex {
            display: flex;
        }

        .justify-center {
            justify-content: center;
        }

        .items-center {
            align-items: center;
        }

        .flex-col {
            flex-direction: column;
        }

        @media print {
            body {
                font-family: 'Nunito', sans-serif !important;
            }

            @page {
                size: auto;
                /* auto is the initial value */
                margin: 0.5in 1in;
                padding: 0;
                /* this affects the margin in the printer settings */
            }

        }

    </style>
</head>

<body>
    <div class="container">
        <div class="hidden-print">
            <div class="toolbar">
                <button class="btn" onclick="window.close()">Cancel</button>
                <button class="btn btn-primary" onclick="printDiv('printableArea')">Print</button>
            </div>
        </div>

        <div id="printableArea">
            @foreach ($employees as $employee)
                <div class="id-card">
                    <div class="id-card__left">
                        <div class="id-card__header">
                            <p>Id No: {{ $employee->readableId }}</p>
                            <span> &nbsp;</span>
                            <p>Date Of Issue: {{ date('d M, Y') }}</p>
                        </div>
                        <div class="id-card__body">
                            <div class="left-section">
                                <div class="info-wrapper relative">
                                    <div class="relative">
                                        <p class="text-uppercase font-bolder">Easy Fashion Ltd</p>
                                        <table>
                                            <tr>
                                                <th class="text-left">Name</th>
                                                <th>:</th>
                                                <td>{{ $employee->name }}</td>
                                            </tr>

                                            <tr>
                                                <th class="text-left">Designation</th>
                                                <th>:</th>
                                                <td>{{ $employee->designation->name }}</td>
                                            </tr>

                                            <tr>
                                                <th class="text-left">Location</th>
                                                <th>:</th>
                                                <td>{{ $employee->location->name }}</td>
                                            </tr>


                                            <tr>
                                                <th class="text-left">Section</th>
                                                <th>:</th>
                                                <td>{{ $employee->section ? $employee->section->name : 'N/A' }}</td>
                                            </tr>

                                            <tr>
                                                <th class="text-left no-wrap">Joining Date</th>
                                                <th>:</th>
                                                <td>{{ $employee->joiningDate->format('d M, Y') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="d-flex flex-col font-bold" style="max-width: 120px;">
                                    <div class="text-center">
                                        <img src="{{ asset('images/signature.jpg') }}" style="height: 20px;"
                                            alt="signature">
                                    </div>
                                    <div class="text-center" style="border-top: 1px solid black;">
                                        Authorized Signature
                                    </div>
                                </div>
                            </div>
                            <div class="right-section">
                                <div class="employee-photo">
                                    <img src="{{ $employee->imageUrl }}" alt="">
                                </div>
                                <div style="margin-top: 10px;">
                                    {!! DNS1D::getBarcodeHTML($employee->id, 'EAN5') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="id-card__right">
                        <div class="id-card__header">
                            <p>Date Of Expire: {{ date('d M, Y', strtotime('+2 years')) }}</p>
                        </div>

                        <div class="id-card__body--back" style="font-size: 10px;">
                            <div class="back-left-section">
                                <div>
                                    <p class="font-bold text-red">If found, please return to this address:</p>
                                    <br>
                                    <p class="text-uppercase font-bolder">Easy Fashion Ltd</p>
                                    <div>
                                        <p>34/B, Malibagh Chowdhurypara, <br> Dhaka-1219</p>
                                        <p><span class="font-bold">Tel:</span> 880-02-9349397, 9332709</p>
                                        <p><span class="font-bold">Email</span> easyfashionwears@gmail.com</p>
                                        <p><span class="font-bold">Website:</span> www.easyfashion.com.bd</p>
                                        <p><span class="font-bold">Hotline:</span> 01711-104489, 01713-429300</p>
                                    </div>
                                </div>
                            </div>
                            <div class="back-right-section" style="padding-left: 10px;">
                                <img src="{{ asset('images/qr.png') }}" style="height: 70px; width:70px;" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
