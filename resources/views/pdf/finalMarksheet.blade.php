<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Sheet</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .container {
            position: relative;
            background-color: white;
            color: rgb(81, 81, 221);
        }

        .container h5 {
            padding-left: 18px;
        }

        u {
            padding-left: 40px;
            padding-right: 40px;
            text-decoration: none;
            border-bottom: 1px dotted;
        }

        .box {
            border: 1px solid rgb(81, 81, 221);
            border-radius: 2px;
            background-color: white;
            color: rgb(81, 81, 221);
        }

        .container table {
            border: 1px solid rgb(81, 81, 221);
            border-radius: 2px;
            background-color: white;
            font-family: 'Times New Roman', Times, serif;
            font-size: smaller;
            padding: 0 10px 10px;
        }

        .text-vertical {
            writing-mode: vertical-rl !important;
            text-orientation: upright !important;
            padding-top: 0;
        }

        .center {
            text-align: center;
        }

        td,
        th {
            padding: 5px;
            /* Reduced padding for smaller row height */
            border: 1px solid;
        }

        .box img {
            padding: 0;
            border-radius: 5px;
        }

        .school_name {
            font-size: 25px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            overflow: hidden;
            display: inline-block;
            max-width: 100%;
        }

        .medium {
            font-size: 23px;
            font-weight: 600;
            padding-bottom: 13px;
        }

        .registration span {
            background-color: #3a33c1;
            border-radius: 25px;
            font-size: 20px;
            font-weight: 500;
            padding: 8px;
        }

        .registration {
            padding: 10px 0;
        }

        .registration span {
            color: white;
        }

        .address {
            font-size: 18px;
            font-weight: 500;
            padding: 5px;
        }

        .application {
            text-decoration: underline;
            font-size: 25px;
            padding-top: 7px;
        }

        .header p {
            margin: 0px;
        }

        .header span {
            line-height: 25px;
        }

        .header {
            padding: 20px;
        }

        /* Watermark styling */
        .watermark {
            position: absolute;
            top: 90;
            left: 300;
            right: 0;
            bottom: 0;
            text-align: center;
            opacity: 0.05;
            z-index: 1;
            color: #000;
            font-size: 50px;
            transform: rotate(-45deg);
            display: flex;
            justify-content: center;
            align-items: center;
            pointer-events: none;
            font-weight: bold;
        }

        /* Ensure content stays on top of watermark */
        .box {
            position: relative;
            z-index: 2;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Watermark added here -->

        <div class="box">
            <div class="watermark">{{ $schoolDetails->school_name }}</div>
            @foreach ($studentResults as $student)
            <div class="header">
                <center>
                    <img align="left" height="100" width="100"
                        src="{{ asset('storage/images/setting/' . $schoolDetails->logo) }}" alt="gpschool.in">
                    <img align="right" height="90" width="70"
                        src="{{ asset('storage/images/registration/' . $student['photo']) }}" alt="">
                    <span class="school_name">{{ $schoolDetails->school_name }}</span>
                    @if ($schoolDetails->medium !== null)
                    <p class="medium">{{ $schoolDetails->medium }}</p>
                    @endif
                    @if ($schoolDetails->registration !== null)
                    <p class="registration"> <span>{{ $schoolDetails->registration }}</span> </p>
                    @endif
                    <p class="address">
                        {{ $schoolDetails->village }},{{ $schoolDetails->post_office }},{{
                        $schoolDetails->police_station }},{{ $schoolDetails->district }},{{ $schoolDetails->pin_code }}
                    </p>
                </center>

                <hr>
                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Progress Report for the session of - <b>{{ $student['session'] }}</b>
                    Name - <b>{{ $student['name'] }}</b>, Fathers name -
                    <b>{{ $student['fathersName'] }}</b>
                    <b>Class- {{ $student['class'] }},
                        @if ($student['section'] != '')
                        Section- {{ $student['section'] }},
                        @endif
                        Roll No- {{ $student['roll_no'] }},Registration No-
                        {{ config('website.registration') . $student['registration'] }},
                    </b> in the exam of
                    <b>{{ $student['exam'] }}.<br>
                </span>
            </div>
            <!-- Main Subjects Section -->
            <table frame="hsides" rules="cols" style="border-collapse: collapse;" align="center">
                <tr>
                    <th rowspan="2">Subject</th>
                    <th rowspan="2">F.M.</th>
                    <th colspan="1">1st Unit Test</th>
                    <th colspan="1">2nd Unit Test</th>
                    <th colspan="7">Annual Examination</th>
                </tr>
                <tr>
                    <td>M.O.</td>

                    <td>M.O.</td>

                    <td colspan="2">F.M.</td>
                    <td>W.</td>
                    <td>O.</td>
                    <td>O.B</td>
                    <td>Grade</td>
                </tr>
                {{--  Dynamic result  --}}
                <tr>
                    <td>Bengali</td>
                    <td>50</td>

                    <td>30</td>

                    <td>30</td>

                    <td>W:80</td>
                    <td>O:20</td>

                    <td>80</td>
                    <td>100</td>
                    <td>20</td>
                    <td>A+</td>
                </tr>
                <tr>
                    <td>Grand Total</td>
                    <td>750</td>
                    <td>485</td>
                    <td>479</td>
                    <td>400</td>
                    <td>100</td>
                    <td>376</td>
                    <td>78</td>
                    <td>468</td>
                    <td>A+</td>
                </tr>
            </table>
            @endforeach
        </div>
    </div>
    <span>***This mark sheet is computer-generated. If any discrepancies are found, please contact the school
        authorities.</span>
</body>

</html>
