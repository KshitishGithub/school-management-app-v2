<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if (session()->has('admin_settings') && !empty(session('admin_settings')[0]['favicon']))
        <link rel="shortcut icon" href="{{ asset('uploads/images/setting/' . session('admin_settings')[0]['favicon']) }}">
    @endif
    <title>ID Card</title>
    <style>
        * {
            margin: 00px;
            padding: 00px;
        }

        .container {
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: left;
            justify-content: start;
            flex-wrap: wrap;
            box-sizing: border-box;
            flex-direction: column;
            margin: 30px;
            overflow: hidden;
        }

        .font {
            height: 385px;
            width: 225px;
            position: relative;
            border-radius: 7px;
            background-image: url('{{ asset('assets/img/id_card/bg-1.png') }}');
            background-size: 225px 375px;
            background-repeat: no-repeat;
            background-color: rgb(219, 217, 217);
            border: 1px solid grey;
            overflow: hidden;
        }

        .companyname {
            text-align: center;
            color: black !important;
            padding: 10px 0 0 10px;
            font-size: 12px;
            /*line-height: 20px;*/
            font-weight: bold !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            left: 50px;
            top: 5px;
            position: absolute;
        }

        .tab {
            /* padding-right: 30px; */
            font-size: 11px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif
        }

        p {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif
        }

        .top img {
            height: 90px;
            width: 90px;
            background-color: #e6ebe0;
            border-radius: 57px;
            position: absolute;
            top: 117px;
            left: 66px;
            object-fit: content;
            border: 3px solid rgba(255, 255, 255, .2);

        }

        .edetails {
            position: absolute;
            top: 245px;
            text-transform: capitalize;
            font-size: 11px;
            text-emphasis: spacing;
            margin-left: 5px;
        }

        .signature {
            position: absolute;
            top: 86%;
            left: 80px;
            height: 80px;
            width: 160px;
            text-align: center;
            font-weight: bold;
        }


        .signature img {
            height: 30px;
            width: 100px;
            border-radius: 7px;

        }


        .barcode img {
            height: 65px;
            width: 65px;
            text-align: center;
            margin: 5px;

        }

        .barcode {
            text-align: center;
            position: absolute;
            top: 86.5%;
            left: 30px;
        }


        .qr img {
            position: absolute;
            top: 88%;
            left: 32%;
            height: 30px;
            width: 120px;
            margin: 20px;
            background-color: white;

        }

        .company-logo img {
            height: 50px;
            width: 50px;
            top: 10px;
            left: 10px;
            position: absolute;
            border-radius: 10px
        }

        .header {
            display: flex;
            height: 40;
        }

        .address {
            height: 43px;
            width: 100%;
            background-color: #E1F1ED;
            position: absolute;
            top: 65px;
            font-size: 11px;
            font-weight: 500;
            padding: 5px;
            overflow: hidden;
            text-align: center;
        }

        .name {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif text-transform: uppercase;

        }

        .student_name {
            width: 100%;
            top: 210px;
            position: relative;
            text-align: center;
        }

        .ename {
            position: absolute;
            width: 100% !important;
            color: black;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="padding">
            <div class="font">
                <div class="header">
                    <div class="company-logo">
                        <img src="{{ asset('uploads/images/setting/' . $settings->logo) }}" alt="">
                    </div>
                    <!--<div class="companyname">{{ $settings->school_name }}</div>-->
                    <div class="companyname">BEGUA KINDERGARTEN <br> & <br>BEGUA KSHATRIYA HOSTEL</div>
                </div>
                <div class="address">
                    <span class="tab">Vill:{{ $settings->village }},
                        Po.:{{ $settings->post_office }},<br>Ps.:{{ $settings->police_station }},
                        Dist.:{{ $settings->district }}, Pin:{{ $settings->pin_code }} </span>
                    <span class="tab">{{ $settings->contact }}</span>
                </div>
                <div class="top">
                    <img src="{{ asset('uploads/images/registration/' . $student->photo) }}" alt="">
                </div>
                <div class="">
                    <div class="student_name">
                        <div class="ename">
                            <span class="name"><b>{{ $student->name }}</b></span> <br />
                            <!--<span class=""><b>Class:{{ $student->class }}, Section:{{ $student->section ?? 'N/A' }}, Roll:{{ $student->roll_no }}</b></span>-->
                            <span class=""><b>Class:{{ $student->class }},
                                    Roll:{{ $student->roll_no }}</b></span>
                        </div>
                    </div>
                    <div class="edetails">
                        <P><b>Mobile No :</b> {{ $student->mobile }}</P>
                        <p><b>DOB :</b> {{ $student->dateOfBirth }}</p>
                        <p><b>Blood Group :</b> {{ $student->blood_group }}</p>
                        <p><b>Address :
                            </b>{{ $student->village }},{{ $student->postOffice }},<br>{{ $student->policeStation }},{{ $student->district }},{{ $student->pin }}
                        </p>
                    </div>

                    <div class="signature">
                        <small>Signature of Principal</small>
                        <img src="{{ asset('uploads/images/committee/' . $committee->signature) }}" alt="">
                    </div>
                    @php
                        // $qrData = urlencode($qrData);
                    @endphp
                    <div class="barcode">
                        {{-- <img src="https://chart.googleapis.com/chart?cht=qr&chl={{ $qrData }}&choe=UTF-8&chs=500x500"
                            alt="QR Code"> --}}
                        {!! $qrData !!}
                            {{-- @php
                           use \Milon\Barcode\DNS1D;

                            $d = new DNS1D();
                            $d->setStorPath(__DIR__.'/cache/');
                            echo $d->getBarcodeHTML('9780691147727', 'EAN13');
                        @endphp --}}


                    </div>
                    <div class="qr">
                        {{-- @php
                            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                            echo '<img style="padding: 5px" src="data:image/png;base64,' . base64_encode($generator->getBarcode('Kshitish Barman', $generator::TYPE_CODE_128)) . '">';
                        @endphp --}}

                        {{-- <img src="{{ url('assets/img/id_card/signature.png') }}" alt=""> --}}

                    </div>


                </div>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.print();
        });
    </script>
</body>

</html>
