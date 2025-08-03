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
                            src="{{ asset('uploads/images/setting/' . $schoolDetails->logo) }}" alt="gpschool.in">
                        <img align="right" height="90" width="70"
                            src="{{ asset('uploads/images/registration/' . $student['photo']) }}" alt="">
                        <span class="school_name">{{ $schoolDetails->school_name }}</span>
                        @if ($schoolDetails->medium !== null)
                            <p class="medium">{{ $schoolDetails->medium }}</p>
                        @endif
                        @if ($schoolDetails->registration !== null)
                            <p class="registration"> <span>{{ $schoolDetails->registration }}</span> </p>
                        @endif
                        <p class="address">
                            {{ $schoolDetails->village }},{{ $schoolDetails->post_office }},{{ $schoolDetails->police_station }},{{ $schoolDetails->district }},{{ $schoolDetails->pin_code }}
                        </p>
                    </center>

                    <hr>
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Progress Report for the session of - <b>{{ $student['session'] }}</b>
                        Name - <b>{{ $student['name'] }}</b>, Father's name -
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
                        <th>SUBJECT</th>
                        <th>WRITTEN MARKS</th>
                        <th>ORAL MARKS</th>
                        <th>TOTAL MARKS</th>
                        <th>PASS MARKS</th>
                        <th>WRITTEN OBTAINED</th>
                        <th>ORAL OBTAINED</th>
                        <th>PERCENTAGE</th>
                        <th>GRADE</th>
                    </tr>

                    <!-- Compulsory Subjects -->
                    <tr class="center">
                        <td colspan="9" style="background-color: lightgray; font-weight: bold;">Compulsory Subjects
                        </td>
                    </tr>
                    @foreach ($student['subjects'] as $subject)
                        @if ($subject['subject_type'] == 1)
                            <tr class="center">
                                <td>{{ $subject['subject'] }}</td>
                                <td>{{ $subject['full_marks'] }}</td>
                                <td>{{ $subject['oral_marks'] }}</td>
                                <td>{{ $subject['total_marks'] }}</td>
                                <td>{{ $subject['pass_marks'] }}</td>
                                <td>{{ $subject['marks_obtained'] }}</td>
                                <td>{{ $subject['oral_marks_obtained'] }}</td>
                                <td>{{ $subject['percentage'] }}%</td>
                                <td>{{ $subject['grade'] }}</td>
                            </tr>
                        @endif
                    @endforeach

                    <!-- Optional Subjects (only displayed if exists) -->
                    @if (count(array_filter($student['subjects'], function ($subject) {
                                return $subject['subject_type'] == 0;
                            })) > 0)
                        <tr class="center">
                            <td colspan="9" style="background-color: lightgray; font-weight: bold;">Optional Subjects
                            </td>
                        </tr>
                        @foreach ($student['subjects'] as $subject)
                            @if ($subject['subject_type'] == 0)
                                <tr class="center">
                                <td>{{ $subject['subject'] }}</td>
                                <td>{{ $subject['full_marks'] }}</td>
                                <td>{{ $subject['oral_marks'] }}</td>
                                <td>{{ $subject['total_marks'] }}</td>
                                <td>{{ $subject['pass_marks'] }}</td>
                                <td>{{ $subject['marks_obtained'] }}</td>
                                <td>{{ $subject['oral_marks_obtained'] }}</td>
                                <td>{{ $subject['percentage'] }}%</td>
                                <td>{{ $subject['grade'] }}</td>
                            </tr>
                            @endif
                        @endforeach
                    @endif

                    <!-- Summary Section -->
                    <tr>
                        <td colspan="6"></td>
                    </tr>
                    <tr class="center">
                        <td colspan="3">OVERALL GRADE : {{ $student['overall_grade'] }}</td>
                        <td colspan="3">TOTAL MARKS OBTAINED: {{ $student['total_marks_obtained'] }}</td>
                        <td colspan="3">OVERALL PERCENTAGE : {{ $student['overall_percentage'] }}%</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            90-100 (AA) Outstanding <br>
                            80-89 (A+) Excellent<br>
                            60-79 (A) Very Good<br>
                            45-59 (B+) Good<br>
                            35-44 (B) Satisfactory<br>
                            25-34 (C) Marginal<br>
                            Below 25 (D) Disqualifies
                        </td>
                        <td colspan="3">
                                1st Division = 60%<br>
                                2nd Division = 45%<br>
                                3rd Division = 34%<br>
                            (O = Oral , W = Written ,<br> P.Edu = Physical Education ,<br> M.O = Marks Obtain ,<br> F.M = Full Marks,<br> H.M. = Highest Marks)
                        </td>
                        <td colspan="2 text-center" align="center">
                            <p style="opacity:0.5" class="text-light">Principal sign and seal</p>
                        </td>
                        <td colspan="2 text-center" align="center">
                            <p style="opacity:0.5" class="text-light">Teacher sign and seal</p>
                        </td>
                    </tr>
                </table>
            @endforeach
        </div>
    </div>
    <span>***This mark sheet is computer-generated. If any discrepancies are found, please contact the school
        authorities.</span>
</body>

</html>
