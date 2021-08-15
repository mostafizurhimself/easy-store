<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EIS Form</title>
</head>
<style>
    body {
        font-family: "DejaVu Sans", sans-serif;
        font-size: 14px;
    }

    @media print {
        body {
            font-size: 11px;
        }
    }

    .container {
        max-width: 800px;
        margin: auto;
        position: relative;
    }

    .float-left {
        float: left;
    }

    .float-right {
        float: right;
    }

    .clearfix {
        clear: both;
    }

    .w-50 {
        width: 50%;
        float: left;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    .font-bold {
        font-weight: bold;
    }

    .title {
        text-align: center;
    }

    /* educational section */

    table {
        border-collapse: collapse;
        border: 1px solid black;
    }

    th,
    td {
        border: 1px solid black;
        padding: .7rem;
    }

    .education {
        table-layout: auto;
        width: 100%;

    }

    /* footer */
    .footer {
        margin-top: 4rem;
        padding: 0 1rem;
    }

    .content-right {
        float: right;
    }


    /* Write Styles here */

</style>

<body>
    <div class="container">

        {{-- Image --}}
        <img src="{{ $employee->imageUrl }}" class="float-right"
            style="height: 100px; width:80px; object-fit:contain; margin-left:-50px; border: 0.5px solid black"
            alt="profile-photo">

        {{-- Form heading --}}

        <div class="title">
            <h2>EIS FORM</h2>
            <h3>Employe Information Summary</h3>
        </div>


        {{-- Information section --}}
        <div class="row" style="margin-top: 2rem">
            <div class="w-50">
                <p><span class="font-bold">Name :</span> {{ $employee->name }} </p>
                <p> <span class="font-bold">Father's Name :</span> {{ $employee->fatherName }}</p>
                <p> <span class="font-bold">Mother's Name :</span> {{ $employee->motherName }}</p>
                <p> <span class="font-bold">Date of Birth :</span> {{ $employee->dob }}</p>
                <p> <span class="font-bold">Blood Group :</span> {{ $employee->bloodGroup }}</p>
                <p> <span class="font-bold">Marital Status :</span> {{ $employee->maritalStatus }}</p>
                <p> <span class="font-bold"> Mobile :</span> {{ $employee->mobile }}</p>
                <p> <span class="font-bold"> Telephone :</span> {{ $employee->telephone }}</p>
                <p> <span class="font-bold"> Emergency Contact :</span> {{ $employee->emergencyMobile }}</p>
                <p> <span class="font-bold">Highest Education :</span> {{ $employee->highestEducation }}</p>
                <p> <span class="font-bold"> Nationality : </span> {{ $employee->nationality }}</p>
            </div>

            <div class="w-50">
                <p> <span class="font-bold">Employee Id No :</span> {{ $employee->readableId }}</p>
                <p> <span class="font-bold">Designation : </span> {{ $employee->designationName }}</p>
                <p> <span class="font-bold"> Work At :</span> {{ $employee->location->name }}</p>
                <p> <span class="font-bold">Dept./Sec./Branch :</span> {{ $employee->departmentName }}</p>
                <p> <span class="font-bold">Nominee Name : </span> {{ $employee->nomineeName }}</p>
                <p> <span class="font-bold">Nominee Contact No :</span> {{ $employee->nomineeMobile }}</p>
                <p> <span class="font-bold">Joining Date : </span> {{ $employee->joiningDate }}</p>
                <p> <span class="font-bold">Present Salary :</span> {{ $employee->salary }}</p>
                <p> <span class="font-bold"> National Id No. :</span> {{ $employee->nid }}</p>
                <p> <span class="font-bold"> Passport No. <span>(if any)</span> : </span> {{ $employee->passport }}
                </p>

            </div>
            <div class="clearfix"></div>
        </div>

        {{-- Address Section --}}
        <div class="address">
            <h2 style="text-align: center">Address Information</h2>

            <div class="row">
                <div class="w-50">
                    <h3 style="text-align: center">
                        <span style="border-bottom: 2px solid black;">Parmanent Address</span>
                    </h3>
                    <p>
                        <span class="font-bold">Vill : </span>
                        {{ $employee->permanentAddress ? $employee->permanentAddress->street : null }}
                    </p>
                    <p>
                        <span class="font-bold">P O :</span>
                        {{ $employee->permanentAddress ? $employee->permanentAddress->po : null }}
                    </p>
                    <p>
                        <span class="font-bold">P S :</span>
                        {{ $employee->permanentAddress ? $employee->permanentAddress->ps : null }}
                    </p>
                    <p>
                        <span class="font-bold">Dist :</span>
                        {{ $employee->permanentAddress ? $employee->permanentAddress->city : null }}
                    </p>
                    <p>
                        <span class="font-bold">Country: </span>
                        {{ $employee->permanentAddress ? Config::get("country.{$employee->permanentAddress->country}", '') : null }}
                    </p>

                </div>

                <div class="w-50">
                    <h3 style="text-align: center">
                        <span style="border-bottom: 2px solid black;">Present Address</span>
                    </h3>
                    <p>
                        <span class="font-bold">Vill : </span>
                        {{ $employee->presentAddress ? $employee->presentAddress->street : null }}
                    </p>
                    <p>
                        <span class="font-bold">P O :</span>
                        {{ $employee->presentAddress ? $employee->presentAddress->po : null }}
                    </p>
                    <p>
                        <span class="font-bold">P S :</span>
                        {{ $employee->presentAddress ? $employee->presentAddress->ps : null }}
                    </p>
                    <p>
                        <span class="font-bold">Dist :</span>
                        {{ $employee->presentAddress ? $employee->presentAddress->city : null }}
                    </p>
                    <p>
                        <span class="font-bold">Country: </span>
                        {{ $employee->presentAddress ? Config::get("country.{$employee->presentAddress->country}", '') : null }}
                    </p>
                </div>
                <div class="clearfix"></div>
            </div>


        </div>

        {{-- Education Section --}}
        <div class="education">
            <h2 style="text-align: center">Educational Qualifications</h2>
            <table class="education">
                <thead>
                    <tr>
                        <th style="width: 15%">Exam Name</th>
                        <th style="width: 35%">Institute Name </th>
                        <th style="width: 20%">Board/University</th>
                        <th style="width: 10%">Result</th>
                        <th style="width: 20%">Passing Year</th>
                    </tr>
                </thead>

                <tbody>
                    @if (count($employee->educations))
                        @foreach ($employee->educations as $education)
                            <tr>
                                <td>{{ $education->examName }}</td>
                                <td>{{ $education->instituteName }}</td>
                                <td>{{ $education->board }}</td>
                                <td>{{ $education->result }}</td>
                                <td>{{ $education->passingYear }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center"> No Data Found</td>
                        </tr>
                    @endif

                </tbody>
            </table>

        </div>

        {{-- footer --}}
        <div class="footer">
            <div class="row" style="margin-top: 2rem">
                <div class="w-50">
                    <h3>
                        <span style="border-top: 2px solid black;">Employee
                            Signature</span>
                    </h3>


                </div>

                <div class="w-50">
                    <h3 style="text-align: right">
                        <span style="border-top: 2px solid black;">Authorized
                            Signature</span>
                    </h3>

                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

</body>

</html>
