<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Schedule</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container mt-5">
        <div class="toolbar hidden-print d-print-none" id="printThis">
            <div class="text-right">
                <button id="printInvoice" class="btn btn-light border"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-light border" id="exportPdf"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
            </div>
            <hr>
        </div>
        <div id="printThis" class="printThis">
            <table class="table table-bordered table-hover">
                <thead>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    @foreach ($dates as $date)
                        <tr style="@if($openingHours->isClosedOn($date))
                            color: #1dbf73;
                        @endif">
                            <td>{{ $date }}</td>
                            <td>
                                @if ($openingHours->isOpenOn($date))
                                    Working day
                                @elseif($openingHours->forDate(new DateTime($date))->getData())
                                    {{ $openingHours->forDate(new DateTime($date))->getData() }}
                                @else
                                    Weekend
                                @endif
                            </td>
                            <td>
                                @if ($openingHours->isOpenOn($date))
                                    Open
                                @else
                                    Holiday
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <script src="{{asset('js/app.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script>
        // Invoice print function

        $(function(){
            $('#printThis').click(function(){
                Popup($('.printThis')[0].outerHTML);
                function Popup(data)
                {
                    window.print();
                    return true;
                }
            });
        })

        window.onload = function () {
        document.getElementById("exportPdf")
            .addEventListener("click", () => {
                const invoice = this.document.getElementById("printThis");
                var opt = {
                    margin: 0.5,
                    filename: "schedule",
                    image: { type: 'jpg', quality: 1 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                };
                html2pdf().from(invoice).set(opt).save();
            })
        }
    </script>


</body>

</html>
