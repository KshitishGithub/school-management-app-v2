<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fees Receipt</title>
    @if (session()->has('admin_settings') && !empty(session('admin_settings')[0]['favicon']))
        <link rel="shortcut icon" href="{{ url('uploads/images/setting/' . session('admin_settings')[0]['favicon']) }}">
    @endif
</head>
<style>
    .header p {
        line-height: 0;
    }

    .registration span {
        background-color: #4537ab;
        border-radius: 25px;
        font-size: 20px;
        font-weight: 500;
        padding: 10px;
        color: rgb(255, 255, 255);
    }

    .registration {
        padding: 10px 0;
    }

    .address {
        font-size: 18px;
        font-weight: 500;
        padding: 5px 0 0 0;
    }

    u {
        text-decoration: none;
        border-bottom: 2px dotted;
    }

    .row h3 {
        line-height: 0%;
        font-size: 25px;
        padding-bottom: 0;
    }

    .row h4 {
        margin-top: 0;
        padding-top: 0;
    }

    .container table {
        border: 1px solid black !important;
        border-radius: 5px;
        background-color: white;
        font-family: 'Times New Roman', Times, serif;
        font-size: smaller;
        padding: 0 10px 10px;
    }

    .first thead th {
        margin: 0;
        padding: 5  px 0 0 0;
    }

    .first td,
    th {
        padding: 5px;
    }

    .second td,
    th {
        padding: 40px;
        border: 1px solid;
    }

    .padding-sm td,
    th {
        padding: 5px;
    }

    table {
        width: 100%;
        border-collapse: collapse !important;
    }

    .center {
        text-align: center !important;
    }

    span {
        font-weight: bolder !important;
    }

    .second thead th {
        font-weight: bolder !important;
    }

    hr {
        border-bottom: 2px dashed #554949 !important;
        text-decoration: none !important;
    }

    .light-color {
        color: rgb(184, 188, 190) !important
    }

    .fees_body td {
        padding: 10px 0 10px 5px !important;
    }

    .flex-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        /* margin-bottom: 4px; */
    }

    .leftside img {
        height: 100px;
    }

    .center-info {
        text-align: center;
        flex-grow: 1;
    }

    .rightside {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
    .school_name{
        font-size:24px;
    }
</style>

<body>
    <div class="container">
        <div class="row">
            <div class="header">
                <div class="flex-container">
                    <div class="leftside">
                        <img src="{{ asset('uploads/images/setting/' . $schoolDetails->logo) }}" alt="gpschool.in">
                    </div>
                    <div class="center-info">
                        <span class="school_name">
                            <!--<h3>{{ $schoolDetails->school_name }}</h3>-->
                           BEGUA KINDERGARTEN AND </br> BEGUA KSHATRIYA HOSTEL
                        </span>
                        @if ($schoolDetails->registration !== null)
                            <p class="registration"> <span>{{ $schoolDetails->registration }}</span> </p>
                        @endif
                        <p class="address">
                            {{ $schoolDetails->village }},{{ $schoolDetails->post_office }},{{ $schoolDetails->police_station }},{{ $schoolDetails->district }},{{ $schoolDetails->pin_code }}
                        </p>
                        <p class="address">Contact: {{ $schoolDetails->contact }}</p>
                        <p class="address">Email: {{ $schoolDetails->email }}</p>
                    </div>
                    <div class="rightside">
                        {!! $qrData !!}
                    </div>
                </div>
            </div>
            <table class="first">
                <tbody>
                    <tr class="padding-sm">
                        <td colspan="2">
                            <span>Session:</span> {{ $data['session'] }}
                        </td>
                        <td align="right" colspan="2">
                            <span>Date:</span>
                            {{ \Carbon\Carbon::parse($data['created_at'])->format('d-m-Y , h:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Name :</span> {{ $data['name'] }}
                        </td>
                        <td align="right">
                            <span>Class :</span> {{ $data['class'] }}
                        </td>
                        <td align="right">
                            <span>Section :</span> {{ $data['section'] ?? 'N/A' }}
                        </td>
                        <td align="right">
                            <span>Roll No :</span> {{ $data['roll_no'] }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <table border="1" class="second">
                <tr class="center padding-sm">
                    <th colspan="2">Fees Type</th>
                    <th colspan="2">Month</th>
                    <th colspan="2">Exam Name</th>
                    <th colspan="2">Total</th>
                </tr>
                {{-- <tr class="fees_body">
                    <td class="center" colspan="3">{{ $data['fees_type'] }}</td>
                    @if ($data['month'])
                        <td class="center" colspan="2">{{ $data['month'] }}</td>
                    @else
                        <td class="center" colspan="2">{{ $data['exam_name'] }}</td>
                    @endif
                    <td width="15%" class="center">{{ $data['amount'] }}.00/-</td>
                </tr> --}}
                @foreach ($processedData as $dataGroup)
                    <tr class="center fees_body">
                        <td colspan="2">{{ $dataGroup['fees']['fees_type'] }}</td>
                        <td colspan="2">{{ $dataGroup['fees']['month'] ?? 'N/A' }}</td>
                        <td colspan="2">{{ $dataGroup['fees']['exam_name'] ?? 'N/A' }}</td>
                        <td colspan="2" width="15%">{{ $dataGroup['fees']['amount'] }}.00/-</td>
                    </tr>
                @endforeach
                <tr class="padding-sm">
                    <td colspan="4"><span>In Words:- {{ convertNumber($totalAmount) }}</span></td>
                    <td colspan="2" align="right" class="center" width="25%"><span>Total amount:</span></td>
                    <td class="center"> {{ $totalAmount }}.00/-</td>
                </tr>
                <tr class="padding-sm">
                    <td width="60%" colspan="4"><u><span>Remarks :</span></u>{{ $data['remarks'] == 'null' ? ' N/A' : $data['remarks']}}
                    </td>
                    <td colspan="4" rowspan="2" align="center">
                        <u class="light-color">Authority Sign and Seal</u>
                    </td>
                </tr>
                <tr class="padding-sm">
                    <td colspan="4">Receved by : <u>{{ $data['receiver'] }}</u></td>
                </tr>
                <tr class="padding-sm">
                    <td colspan="8">N.B : <u>FEES ARE NOT REFUNDABLE .</u></td>
                </tr>
            </table>
            <hr>
        </div>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.print();
        });
    </script>
</body>

</html>
