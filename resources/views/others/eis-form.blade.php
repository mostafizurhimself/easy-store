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
        margin-top: 1.5rem;
        padding: 0 1rem;
    }

    .content-right{
        float: right;
}


    /* Write Styles here */

</style>

<body>
    <div class="container">

        {{-- Image --}}
        <img src="https://cdn3.vectorstock.com/i/1000x1000/26/87/user-icon-man-profile-human-person-avatar-vector-10552687.jpg"
            class="float-right" style="height: 100px; margin-left:-50px" alt="profile-photo">

        {{-- Form heading --}}

        <div class="title">
            <h2>EIS FORM</h2>
            <h3>Employe Information Summary</h3>
        </div>


        {{-- Information section --}}
        <div class="row" style="margin-top: 2rem">
            <div class="w-50">
                <p><span class="font-bold">Name :</span> </p>
                <p> <span class="font-bold">Father's Name :</span> </p>
                <p> <span class="font-bold">Mother's Name :</span> </p>
                <p> <span class="font-bold">Date of Birth :</span> </p>
                <p> <span class="font-bold">Blood Group :</span> </p>
                <p> <span class="font-bold">Marital Status :</span> </p>
                <p> <span class="font-bold">Contact Number :</span> </p>
                <p> <span class="font-bold"> Emergency Number :</span> </p>
                <p> <span class="font-bold">Highest Education :</span> </p>
                <p> <span class="font-bold"> Nationality : </span></p>
                <p> <span class="font-bold"> National Id No. :</span> </p>
                <p> <span class="font-bold"> Passport No. <span>(if any)</span> : </span></p>

            </div>

            <div class="w-50">
                <p> <span class="font-bold">Employee Id No :</span> </p>
                <p> <span class="font-bold">Designation : </span> </p>
                <p> <span class="font-bold"> Shift :</span> </p>
                <p> <span class="font-bold">Dept./Sec./Branch :</span> </p>
                <p> <span class="font-bold">Nominee Name : </span> </p>
                <p> <span class="font-bold">Nominee Contact No :</span> </p>
                <p> <span class="font-bold">Joining Date : </span> </p>
                <p> <span class="font-bold">Present Salary :</span> </p>

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
                    <p> <span class="font-bold">Vill : </span></p>
                    <p> <span class="font-bold">P O :</span></p>
                    <p> <span class="font-bold">P S :</span></p>
                    <p> <span class="font-bold">Dist :</span> </p>
                    <p> <span class="font-bold">Country: </span></p>

                </div>

                <div class="w-50">
                    <h3 style="text-align: center">
                        <span style="border-bottom: 2px solid black;">Present Address</span>
                    </h3>
                    <p> <span class="font-bold">Vill/Street :</span> </p>
                    <p> <span class="font-bold">P O :</span> </p>
                    <p> <span class="font-bold"> P S :</span> </p>
                    <p> <span class="font-bold">Dist :</span> </p>
                    <p> <span class="font-bold">Country :</span> </p>
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
                        <th style="width: 15%">Name of Exam</th>
                        <th style="width: 35%">Institute Name </th>
                        <th style="width: 20%">Board/University</th>
                        <th style="width: 10%">Result</th>
                        <th style="width: 20%">Passing Year</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>SSC/DAKHIL</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>HSC/ALIM</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>BA/HONR'S</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>MA</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                </tbody>
            </table>

        </div>

        {{-- footer --}}
        <div class="footer">


            <div class="row">
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

            {{-- <div class="row">
                <div class="w-50">
                    <span class="w-50 text-left">
                        <span class="font-bold" style="border-top: 2px solid black; padding-top: 1rem">Employee
                            Signature</span>
                    </span>

                </div>

                <div class="w-50 ">
                    <span class="w-50">
                        <span class="font-bold" style="border-top: 2px solid black; padding-top: 1rem">Authorized
                            Signature</span>
                    </span>
                </div>
                <div class="clearfix"></div>
            </div> --}}



        </div>
    </div>

</body>

</html>
