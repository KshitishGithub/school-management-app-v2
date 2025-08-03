<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration Form</title>
    <style>
        .header p {
            line-height: 0;
        }

        .school_name {
            font-size: 25px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            overflow: hidden;
            display: inline-block;
            max-width: 100%;
            padding-bottom:30px;
        }

        .medium {
            font-size: 23px;
            font-weight: 600;
            padding-bottom: 13px
        }

        .registration span {
            background-color: #a3ab37;
            border-radius: 25px;
            font-size: 20px;
            font-weight: 500;
            padding: 10px;
            color: rgb(0, 0, 0);
        }

        .registration {
            padding-bottom: 18px
        }

        .registration span {
            color: white;
        }

        .address {
            font-size: 18px;
            font-weight: 500;
            padding: 5px 0;
        }

        .application {
            text-decoration: underline;
            font-size: 25px;
            padding-top: 7px;
        }

        /* Table Style */
        .table {
            border: 1px solid rgb(130, 127, 127) !important;
            border-collapse: collapse;
        }

        .table tr td {
            font-size: 18px;
            padding: 10px;
            border: 1px solid rgb(130, 127, 127) !important;
        }

        .table u {
            font-family: 'Times New Roman', Times, serif;
            text-decoration: none;
            border-bottom: 2px dotted #555050;
            padding-left: 10px;
            padding-right: 20px;
        }

        .label {
            font-weight: 300;
        }

        .table tr th {
            font-size: 20px;
            letter-spacing: 2px;
            padding: 10px 0px !important;
            color: white !important;
            text-decoration: underline;
            background-color: rgba(50, 53, 203, 0.686);
        }

        hr {
            border: 1px solid rgb(58, 53, 53);
        }
    </style>
</head>

<body>
    <div class="contariner">
        <div class="header">
            <center>
                <img align="left" height="100" src="{{ asset('uploads/images/setting/' . $schoolDetails->logo) }}"
                    alt="">
                <img align="right" height="100" src="{{ url('assets/img/profiles/demo.png') }}" alt="">
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
                <p class="address">Contact: {{ $schoolDetails->contact }}</p>
                <p class="address">Email: {{ $schoolDetails->email }}</p>
                <p class="application">Application Form For Admission</p>
            </center>
        </div>
        <div class="body">
            <table class="table">
                <tr>
                    <th colspan="6">Personal Details</th>
                </tr>
                <tr>
                    <td class="label" colspan="3">Session : <span></span>
                    </td>
                    <td align="left" class="label" colspan="3">Registration No :
                        <span>{{ config('website.registration') }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="label" colspan="3">Class : <span></span>
                    </td>
                    <td align="left" class="label" colspan="3">Section :
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td class="label" width="23%" colspan="1">Student Name : </td>
                    <td colspan="5"><u></u></td>
                </tr>
                <tr>
                    <td class="label" colspan="1">Father's Name : </td>
                    <td colspan="5"><u></u></td>
                </tr>
                <tr>
                    <td class="label" colspan="1">Qualification : </td>
                    <td colspan="2"><u></u></td>
                    <td class="label" colspan="1">Occupation : </td>
                    <td colspan="2"><u></u></td>
                </tr>
                <tr>
                    <td class="label" colspan="1">Mother's Name : </td>
                    <td colspan="5"><u></u></td>
                </tr>
                <tr>
                    <td class="label" colspan="1">Qualification : </td>
                    <td colspan="2"><u></u></td>
                    <td class="label" colspan="1">Occupation : </td>
                    <td colspan="2"><u></u></td>
                </tr>
                <tr>
                    <td class="label" colspan="1">Mobile No :</td>
                    <td colspan="2"><u></u></td>
                    <td class="label" colspan="1">WhatsApp No :</td>
                    <td colspan="2"><u></u></td>
                </tr>
                <tr>
                    <td class="label" colspan="1">Date of Birth :</td>
                    <td colspan="2"><u></u></td>
                    <td class="label" colspan="1">Religion :</td>
                    <td colspan="2"><u></u></td>
                </tr>
                <tr>
                    <td class="label" colspan="1">Caste :</td>
                    <td colspan="2"><u></u></td>
                    <td class="label" colspan="1">Nationality :</td>
                    <td colspan="2"><u></u></td>
                </tr>
                <tr>
                    <td class="label" colspan="1">Blood Group :</td>
                    <td colspan="2"><u></u></td>
                    <td class="label" colspan="1">Gender :</td>
                    <td colspan="2"><u></u></td>
                </tr>
                <tr>
                    <td class="label" colspan="1">Aadhar :</td>
                    <td colspan="5"><u></u></td>
                </tr>
                <tr>
                    <td class="label" colspan="1">Transport :</td>
                    <td colspan="1"><u></u></td>
                    <td class="label" colspan="1">Hostel :</td>
                    <td colspan="1"><u></u></td>
                    <td class="label" colspan="1">Mess :</td>
                    <td colspan="1"><u></u></td>
                </tr>
                <tr>
                    <th colspan="6" class="details">Address Details</th>
                </tr>
                <tr>
                    <td class="label" colspan="1">Village : </td>
                    <td colspan="2"><u></u> </td>
                    <td class="label" colspan="1">Post Office : </td>
                    <td colspan="2"><u></u></td>
                </tr>
                <tr>
                    <td class="label" colspan="1">Police Station : </td>
                    <td colspan="1"><u></u></td>
                    <td class="label" colspan="1">District : </td>
                    <td colspan="1"><u></u></td>
                    <td class="label" colspan="1">Pin : </td>
                    <td colspan="1"><u></u> </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <p>I hereby declare that the above particulrs of facts and information stated are correct to the
                            best of my belief and knowledge. I am also ready to follow up all the rules and regulations
                            of the school. <br><br> <b>NOTE:</b>Attached Documents: Xerox copy of Birth Certificate ,
                            Aadhar Card and 2 copy recent color passport size photo.</p>
                    </td>
                </tr>
                <tr>
                    <td class="label" colspan="3">Date :
                        <u></u>
                    </td>
                    <td style="padding-top: 40px" align="center" colspan="3" class="label guardian">
                        <hr> Guardian Signature
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
