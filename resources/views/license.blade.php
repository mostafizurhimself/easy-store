<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>License</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container mt-5" style="max-width: 600px">
        @if ($license->expirationDate->greaterThan(\Carbon\Carbon::now()))
            <div class="alert alert-info text-center" role="alert">
                Your transaction details is pending for verification.
            </div>
        @else
            <div class="alert alert-danger text-center" role="alert">
                License Expired! Please add your payment details.
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="post" action="/license">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Package</label>
                        <select name="package" class="form-control">
                            <option class="standard">Standard</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Invoice No</label>
                        @if (isset($license) && $license->expirationDate->greaterThan(\Carbon\Carbon::now()))
                            <input type="text" name="invoice_no" class="form-control" value="{{ $license->invoiceNo }}">
                        @else
                            <input type="text" name="invoice_no" class="form-control" value="{{ old('invoice_no') }}">
                        @endif
                        <span class="text-danger">{{ $errors->first('invoice_no') }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transaction Id</label>
                        @if (isset($license) && $license->expirationDate->greaterThan(\Carbon\Carbon::now()))
                            <input type="text" name="transaction_id" class="form-control" value="{{ $license->transactionId }}">
                        @else
                            <input type="text" name="transaction_id" class="form-control" value="{{ old('transaction_id') }}">
                        @endif
                        <span class="text-danger">{{ $errors->first('transaction_id') }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        @if (isset($license) && $license->expirationDate->greaterThan(\Carbon\Carbon::now()))
                            <input type="text" name="amount" class="form-control" value="{{ $license->amount }}">
                        @else
                            <input type="text" name="amount" class="form-control" value="{{ old('amount') }}">
                        @endif
                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="package" class="form-control" disabled>
                            <option class="inactive" @if($license->status == "inactive") selected @endif>Inactive</option>
                            <option class="active" @if($license->status == "active") selected @endif >Active</option>
                        </select>
                        <span class="text-danger">{{ $errors->first('status') }}</span>
                    </div>

                    <button type="submit" class="btn btn-block btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
