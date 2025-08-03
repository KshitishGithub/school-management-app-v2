<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <title>Reset your password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ededed;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            border-collapse: collapse;
            width: 100%;
        }

        .header {
            background-color: rgb(203, 217, 73);
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-width: 100%;
            height: auto;
        }

        .header h1 {
            font-weight: 800;
            margin-top: 10px;
        }

        .body {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #ccc;
            border-top: none;
        }

        .body p {
            color: #1A3556;
            margin-bottom: 10px;
        }

        .footer {
            background-color: #1A3556;
            color: #fff;
            padding: 10px 20px;
            border-radius: 0 0 10px 10px;
            text-align: center;
        }

        .social-icons {
            font-size: 18px;
            margin-top: 10px;
        }

        .social-icons a {
            text-decoration: none;
            color: #fff;
            margin: 0 10px;
        }

        .social-icons a:hover {
            color: #FF3131;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #0ee668;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn:hover {
            background-color: #e60000;
        }

        /* Media query for responsiveness */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
    <table class="container">
        <tr>
            <td class="header" colspan="2">
                {{-- <img src="{{ $mailData['url'] }} / {{ $mailData['settings']->logo }}" alt=""> --}}
                <h1>Reset Password</h1>
            </td>
        </tr>
        <tr>
            <td class="body" colspan="2">
                <p>Dear,</p>
                <p>{{ $mailData['name'] }}</p>
                <p>Recently, a request was submitted to reset the password for your account with email: <span
                        style="color: #FF3131;">{{ $mailData['email'] }}</span></p>
                <p>If you did not make the request, just ignore this email. Otherwise, you can reset your password using
                    this button: <a href="{{ url('/user/update_password?token=').$mailData['token'] }}" class="btn">Click Here</a></p>
                <p>Regards,</p>
                <p>{{ $mailData['settings']->school_name }}</p>
            </td>
        </tr>
        <tr>
            <td class="footer" colspan="2">
                <div>
                    <i class="fa-solid fa-location-dot"></i>
                    <p>{{ $mailData['settings']->village }},{{ $mailData['settings']->post_office }},{{ $mailData['settings']->police_station }},{{ $mailData['settings']->district }},{{ $mailData['settings']->pin_code }}</p>
                </div>
                <div>
                    <p><i class="fa-regular fa-envelope"></i> {{ $mailData['settings']->email }}</p>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
