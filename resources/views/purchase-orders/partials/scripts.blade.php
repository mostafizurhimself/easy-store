<script src="{{asset('js/app.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
<script>
    $(function(){
        $('#printInvoice').click(function(){
            Popup($('.invoice')[0].outerHTML);
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
            const invoice = this.document.getElementById("invoice");
            var opt = {
                margin: 0.5,
                filename: "{{$purchaseOrder->readableId}}",
                image: { type: 'jpg', quality: 1 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().from(invoice).set(opt).save();
        })
    }
</script>
