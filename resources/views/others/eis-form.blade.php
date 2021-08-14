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
    }

    .container {
        width: 90%;
        margin: auto;
        padding: 2rem;
    }

    .title {
        margin-top: 4rem;
        justify-content: center;
    }


    .title h2 {
        margin-top: 0;
        margin-bottom: 0;
    }

    .title h4 {
        margin-top: 0;
        margin-bottom: 0;
    }


    .img {
        position: absolute;
        right: 0;
        top: 0;
        margin-right: 6rem;
        margin-top: 3rem;

    }

    /* Info section */
    .flex-container {
        display: flex;
        flex-wrap: nowrap;
        font: bold;
    }

    .flex-container>div {
        width: 50%;
        margin: 10px;
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
        width: 100%
    }

    /* footer */
    .footer{
        margin-top: 4rem;
        display: flex;
        justify-content: space-between;
        padding: 0 3rem;
    }

    .signature-line{
        display: inline-block;
        height: 2px;
        background: black;
        border-radius: 5px;

    }

    /* Write Styles here */

</style>

<body>
    <div class="container">

        {{-- Form heading --}}

        <div class="title">
            <h2 style="text-align: center">EIS FORM</h2>
            <h4 style="text-align: center">Employe Information Summary</h4>
        </div>
        {{-- Image --}}
        <div class="img">
            <img src="https://cdn3.vectorstock.com/i/1000x1000/26/87/user-icon-man-profile-human-person-avatar-vector-10552687.jpg"
                style="height: 200px; margin-left:2rem" alt="">
        </div>

        {{-- Information section --}}
        <div class="flex-container" style="margin-top: 2rem">
            <div class="left-section">
                <p>Name : </p>
                <p>Father's Name : </p>
                <p>Mother's Name : </p>
                <p>Date of Birth : </p>
                <p>Blood Group : </p>
                <p>Nerital Status : </p>
                <p>Contact Number : </p>
                <p>Emergency Number : </p>
                <p>Highest Education : </p>
                <p>National Id No. : </p>
                <p>Passport No. <span>(if any)</span> : </p>
            </div>

            <div class="right-section">
                <p>Employee Id No : </p>
                <p>Designation : </p>
                <p>Work At : </p>
                <p>Dept./Sec./Branch : </p>
                <p>Nominee Name : </p>
                <p>Nominee Contact No : </p>
                <p>Joining Date : </p>
                <p>Present Salary : </p>
            </div>
        </div>

        {{-- Address Section --}}
        <div class="address">
            <h3 style="text-align: center">Address Information</h3>

            <div class="flex-container">
                <div class="left-section">
                    <h4 style="text-align: center; ">Parmanent Address</h4>
                    <p>Vill : </p>
                    <p>P O : </p>
                    <p>PS : </p>
                    <p>Dist : </p>
                    <p>Contact No : </p>

                </div>

                <div class="right-section">
                    <h4 style="text-align: center; ">Present Address</h4>
                    <p>Vill/House No : </p>
                    <p>Road No : </p>
                    <p>P O : </p>
                    <p>P S : </p>
                    <p>Dist : </p>
                    <p>Contact No : </p>
                </div>

            </div>


        </div>

        {{-- Education Section --}}
        <div class="education">
            <h3 style="text-align: center">Educational Qualifications</h3>
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
            <span class="signature-line"><p>Employee Signature</p></span>
            <span class="signature-line"><p>Authorized Signature</p></span>
        </div>

    </div>

</body>

</html>
