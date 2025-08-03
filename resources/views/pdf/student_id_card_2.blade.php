<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="idCard.css">
    <title>ID Card</title>
    <style>
        * {
            margin: 00px;
            padding: 00px;
            box-sizing: content-box;
        }

        .container {
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row;
            flex-flow: wrap;

        }

        .font {
            height: 375px;
            width: 250px;
            position: relative;
            border-radius: 10px;
        }

        .top {
            height: 30%;
            width: 100%;
            background-color: #795281;
            position: relative;
            z-index: 5;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .bottom {
            height: 70%;
            width: 100%;
            background-color: rgb(243, 240, 240);
            position: absolute;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        .top img {
            height: 130px;
            width: 100px;
            background-color: #e6ebe0;
            border-radius: 6px;
            position: absolute;
            top: 30px;
            left: 75px;
        }

        .bottom p {
            position: relative;
            top: 60px;
            text-align: center;
            text-transform: capitalize;
            font-weight: bold;
            font-size: 20px;
            text-emphasis: spacing;
        }

        .bottom .desi {
            font-size: 12px;
            color: grey;
            font-weight: normal;
        }

        .bottom .no {
            font-size: 15px;
            font-weight: normal;
        }

        .barcode img {
            height: 85px;
            width: 85px;
            text-align: center;
            margin: 5px;
        }

        .barcode {
            text-align: center;
            position: relative;
            top: 70px;
        }

        .back {
            height: 375px;
            width: 250px;
            border-radius: 10px;
            background-color: #795281;

        }

        .qr img {
            height: 80px;
            width: 100%;
            margin: 20px;
            background-color: white;
        }

        .Details {
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 25px;
        }


        .details-info {
            color: white;
            text-align: left;
            padding: 5px;
            line-height: 20px;
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
            line-height: 22px;
        }

        .logo {
            height: 40px;
            width: 150px;
            padding: 40px;
        }

        .logo img {
            height: 100%;
            width: 100%;
            color: white;

        }

        .padding {
            padding-right: 20px;
        }

        @media screen and (max-width:400px) {
            .container {
                height: 130vh;
            }

            .container .front {
                margin-top: 50px;
            }
        }

        @media screen and (max-width:600px) {
            .container {
                height: 130vh;
            }

            .container .front {
                margin-top: 50px;
            }

        }
    </style>
</head>

<body>
    <div class="container">
        <div class="padding">
            <div class="font">
                <div class="top">
                    <img src="{{ asset('uploads/images/registration/' . $student->photo) }}">
                </div>
                <div class="bottom">
                    <p>{{ $student->name }}</p>
                    <p class="desi">{{ $student->class}}-{{$student->section ?? "N/A" }}-{{$student->roll_no }}</p>
                    {{-- @php
                        $qrData = urlencode($qrData);
                    @endphp --}}
                    <div class="barcode">
                        {{-- <img src="https://chart.googleapis.com/chart?cht=qr&chl={{ $qrData }}&choe=UTF-8&chs=500x500"
                            alt="QR Code"> --}}
                            {!! $qrData !!}
                    </div>
                    <br>
                    <p class="no">Session: {{ $student->session }}</p>
                    <p class="no">Date of Birth: {{ $student->dateOfBirth }}</p>

                </div>
            </div>
        </div>
        <div class="back">
            <h1 class="Details">information</h1>
            <hr class="hr">
            <div class="details-info">
                <p><b>Fathers' Name : </b></p>
                <p>{{ $student->fathersName }}</p>
                <p><b>Mobile No: </b></p>
                <p>{{ $student->mobile }}</p>
                <p><b>Office Address:</b></p>
                <p>{{ $student->village }},{{ $student->postOffice }},<br>{{ $student->policeStation }},{{ $student->district }},{{ $student->pin }}</p>
            </div>
            <div class="logo">
                @php
                    $Color = [255, 255, 255];
                    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                    echo '<img style="padding: 5px" src="data:image/png;base64,' .
                        base64_encode($generator->getBarcode('Kshitish Barman', $generator::TYPE_CODE_128,3, 50, $Color)) .
                        '">';
                @endphp
            </div>


            <hr>
        </div>
    </div>
    </div>
</body>

</html>
