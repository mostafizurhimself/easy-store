<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title')</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        h1,
        p {
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
        }

        p {
            margin-top: 0;
        }

        table {
            width: 100%;
        }

        table thead {
            text-align: left;
        }

        tr:nth-child(2n + 1) {
            background: #dadada;
        }

        tr:first-child {
            font-weight: bold
        }

        th,
        td {
            padding: 1rem;
        }

        .tfoot {
            font-weight: bold;

        }

    </style>
</head>

<body>
    @yield('content')
</body>

</html>
