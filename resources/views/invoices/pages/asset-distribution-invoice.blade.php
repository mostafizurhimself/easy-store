@extends('invoices.layout')

@section('title')
    {{$invoice->readableId}}
@endsection


@section('content')

<div id="inventory-invoice">

    <div class="toolbar hidden-print d-print-none">
        <div class="text-right">
            <button id="printInvoice" class="btn btn-light border"><i class="fa fa-print"></i> Print</button>
            <button class="btn btn-light border"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
        </div>
        <hr>
    </div>
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col">
                        <a target="_blank" href="#">
                            <h1>COMPANY LOGO</h1>
                            </a>
                    </div>
                    <div class="col company-details">
                        <h2 class="name">
                            <a target="_blank" href="#">
                            Company Name
                            </a>
                        </h2>
                        <div>26 Tower Name, City 123456, INDIA</div>
                        <div>(123) 456-789</div>
                        <div>info@company.com</div>
                    </div>
                </div>
            </header>
            <main>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="text-gray-light">INVOICE TO:</div>
                        <h2 class="to">Rohit Chauhan</h2>
                        <div class="address">B-56 Bulding Name, City, State - India</div>
                        <div class="email"><a href="mailto:test@example.com">test@example.com</a></div>
                    </div>
                    <div class="col invoice-details">
                        <h1 class="invoice-id">INVOICE 121</h1>
                        <div class="date">Date of Invoice: 28/11/2018</div>
                        <div class="date">Due Date: 28/11/2018</div>
                    </div>
                </div>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th>SR NO.</th>
                            <th class="text-left">DESCRIPTION</th>
                            <th class="text-right">PRICE</th>
                            <th class="text-right">TAX (13%)</th>
                            <th class="text-right">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="no">01</td>
                            <td class="text-left"><h3>Description 1</h3>Testing Description 1</td>
                            <td class="unit">₹ 0.00</td>
                            <td class="tax">10%</td>
                            <td class="total">₹ 0.00</td>
                        </tr>
                        <tr>
                            <td class="no">02</td>
                            <td class="text-left"><h3>Description 2</h3>Testing Description 2</td>
                            <td class="unit">₹ 40.00</td>
                            <td class="tax">13%</td>
                            <td class="total">₹ 1,200.00</td>
                        </tr>
                        <tr>
                            <td class="no">03</td>
                            <td class="text-left"><h3>Description 3</h3>Testing Description 3</td>
                            <td class="unit">₹ 40.00</td>
                            <td class="tax">13%</td>
                            <td class="total">₹ 3,200.00</td>
                        </tr>
                        <tr>
                            <td class="no">04</td>
                            <td class="text-left"><h3>Description 4</h3>Testing Description 4</td>
                            <td class="unit">₹ 40.00</td>
                            <td class="tax">13%</td>
                            <td class="total">₹ 800.00</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2">SUBTOTAL</td>
                            <td>₹ 5,200.00</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2">TAX 25%</td>
                            <td>₹ 1,300.00</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2">GRAND TOTAL</td>
                            <td>₹ 6,500.00</td>
                        </tr>
                    </tfoot>
                </table>
                <div class="thanks">Thank you!</div>
                <div class="notices">
                    <div>NOTICE:</div>
                    <div class="notice">System Generated Invoice.</div>
                </div>
            </main>
            <footer>
                Invoice was generated on a computer and is valid without the signature and seal.
            </footer>
        </div>
        <div></div>
    </div>
</div>
@endsection
