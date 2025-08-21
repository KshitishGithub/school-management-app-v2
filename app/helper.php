<?php

use Illuminate\Support\Facades\DB;
use App\Models\setting;
use Illuminate\Support\Facades\Http;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Cache;


// ! Print functionality
if (!function_exists('p')) {
    function p($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

// ! valueExistsInArray
if (!function_exists('valueExistsInArray')) {
    function valueExistsInArray($value, $array)
    {
        return in_array($value, $array);
    }
}


// ! Number to text conversion
if (!function_exists('convertNumber')) {
    function convertNumber($num = false)
    {
        $num = str_replace(array(',', ''), '', trim($num));
        if (!$num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array(
            '',
            'one',
            'two',
            'three',
            'four',
            'five',
            'six',
            'seven',
            'eight',
            'nine',
            'ten',
            'eleven',
            'twelve',
            'thirteen',
            'fourteen',
            'fifteen',
            'sixteen',
            'seventeen',
            'eighteen',
            'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array(
            '',
            'thousand',
            'million',
            'billion',
            'trillion',
            'quadrillion',
            'quintillion',
            'sextillion',
            'septillion',
            'octillion',
            'nonillion',
            'decillion',
            'undecillion',
            'duodecillion',
            'tredecillion',
            'quattuordecillion',
            'quindecillion',
            'sexdecillion',
            'septendecillion',
            'octodecillion',
            'novemdecillion',
            'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ($hundreds == 1 ? '' : '') . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ($tens < 20) {
                $tens = ($tens ? ' and ' . $list1[$tens] . ' ' : '');
            } elseif ($tens >= 20) {
                $tens = (int)($tens / 10);
                $tens = ' and ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        $words = implode(' ',  $words);
        $words = preg_replace('/^\s\b(and)/', '', $words);
        $words = trim($words);
        $words = ucfirst($words);
        // $words = $words . ".";
        return $words . ' rupees only.';
    }
}



//! header Sms sending
// function headersms($header, $smsid, $value = [], $mobile = [])
// {
//     $smsvalues = implode('|', $value);
//     $mobile = implode(',', $mobile);

//     $fields = array(
//         "route" => "dlt",
//         "sender_id" => "$header",
//         "message" => "$smsid",
//         "variables_values" => $smsvalues,
//         // "variables_values" => $value,
//         "flash" => 0,
//         "numbers" => $mobile,
//     );
//     // print_r($fields);
//     // die();

//     $curl = curl_init();
//     curl_setopt_array($curl, array(
//         CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_ENCODING => "",
//         CURLOPT_MAXREDIRS => 10,
//         CURLOPT_TIMEOUT => 30,
//         CURLOPT_SSL_VERIFYHOST => 0,
//         CURLOPT_SSL_VERIFYPEER => 0,
//         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//         CURLOPT_CUSTOMREQUEST => "POST",
//         CURLOPT_POSTFIELDS => json_encode($fields),
//         CURLOPT_HTTPHEADER => array(
//             "authorization:hmlbFHwuQK79aBykpARfDrWtTJ5GvOc4Nj0Z1z3i6Cq2VxonXsFO1us5HXwavx6PIMWn7JDZTRmB4bor",
//             "accept: */*",
//             "cache-control: no-cache",
//             "content-type: application/json"
//         ),
//     ));
//     $response = curl_exec($curl);
//     $err = curl_error($curl);
//     curl_close($curl);
//     if ($err) {
//         echo json_encode(array('status' => false, 'message' => 'SMS not sent', 'error' => $err));
//     } else {
//         echo json_encode(array('status' => true, 'message' => 'SMS sent successfully'));
//     }
// }


//! OTP Sms sending
// function otpsms($value, $mobile)
// {
//     $fields = array(
//         "variables_values" => $value,
//         "route" => "otp",
//         "numbers" => $mobile,
//     );
//     // print_r($fields);
//     // die();

//     $curl = curl_init();
//     curl_setopt_array($curl, array(
//         CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_ENCODING => "",
//         CURLOPT_MAXREDIRS => 10,
//         CURLOPT_TIMEOUT => 30,
//         CURLOPT_SSL_VERIFYHOST => 0,
//         CURLOPT_SSL_VERIFYPEER => 0,
//         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//         CURLOPT_CUSTOMREQUEST => "POST",
//         CURLOPT_POSTFIELDS => json_encode($fields),
//         CURLOPT_HTTPHEADER => array(
//             "authorization:hmlbFHwuQK79aBykpARfDrWtTJ5GvOc4Nj0Z1z3i6Cq2VxonXsFO1us5HXwavx6PIMWn7JDZTRmB4bor",
//             "accept: */*",
//             "cache-control: no-cache",
//             "content-type: application/json"
//         ),
//     ));
//     $response = curl_exec($curl);
//     $err = curl_error($curl);
//     curl_close($curl);
//     if ($err) {
//         echo json_encode(array('status' => false, 'message' => 'SMS not sent', 'error' => $err));
//     } else {
//         echo json_encode(array('status' => true, 'message' => 'SMS sent successfully'));
//     }
// }


//! Firebase Push notification
function FirebasePushNotification($registration_ids = [], $title = 'School Siksha Notification', $body = '', $image = '')
{
    $tokens = [];

    // Fetch device tokens from the database
    foreach ($registration_ids as $registration_id) {
        $deviceToken = DB::table('device_token')
            ->where('registration_id', operator: $registration_id)
            ->value('device_token');

        if ($deviceToken) {
            $tokens[] = $deviceToken;
        }
    }

    // No tokens found
    if (empty($tokens)) {
        return json_encode(['status' => false, 'message' => 'No device tokens available']);
    }

    // Load Firebase access token from settings
    // $accessToken = \App\Models\Setting::value('firebase_token'); // Adjust if needed
    $accessToken = getFirebaseAccessToken();

    if (!$accessToken) {
        return json_encode(['status' => false, 'message' => 'Firebase access token not found']);
    }

    // Firebase project ID
    $projectId = '549430247722'; // Replace with your actual project ID

    $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

    $responses = [];

    foreach ($tokens as $token) {
        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'image' => $image ?: null,
                ],
                'android' => [
                    'priority' => 'HIGH',
                ],
                'apns' => [
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                        ],
                    ],
                ],
            ]
        ];

        $response = Http::withToken($accessToken)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $payload);

        if ($response->successful()) {
            $responses[] = ['token' => $token, 'status' => true];
        } else {
            $responses[] = [
                'token' => $token,
                'status' => false,
                'error' => $response->json()
            ];
        }
    }

    return json_encode([
        'status' => true,
        'message' => 'Push notifications processed',
        'results' => $responses
    ]);
}
// fwg_aEbXQfijRLIKr4mZjV:APA91bG9T-Eh2eDGwl2vBQ_3OxdqQtHh3y8TyFcJI11JYdCvBrC3Kqa65rFdGTVvvM3If0guh4kp4_Vc6PD_7b0UCfBwrdzLVTOx8UtOccTr6TpZld221o1vUqufLz41gtGP9BlRQIJR
// e6qczvnGR4Gwz4rJNBKNTR:APA91bH0jd4-UYz_Kboc7CEGFljyrbj7Yfh23A9ZDL9RMCTO9KGB0P2swi11oh0_-LdgC4FbZ5uGPAo5-60vPq54ResmcJc9uaf8n6M5OsBQqVTFCJrO4Og
// ! One signal push notification --------------------------------
function OneSignalPushNotification($headings, $content, $bigPicture, $largeIcon)
{
    $apiKey = setting::all()[0]['one_signal_api_key'];
    $appId = setting::all()[0]['one_signal_app_id'];

    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => 'Basic ' . $apiKey,
    ];


    $body = [
        'app_id' => $appId,
        'included_segments' => ['ActiveUser'],
        'headings' => ['en' => $headings],
        'contents' => ['en' => $content],
        'big_picture' => $bigPicture,
        'large_icon' => $largeIcon,
    ];

    $response = Http::withHeaders($headers)
        ->post('https://onesignal.com/api/v1/notifications', $body);

    if ($response->status() == 200) {
        return json_encode(['status' => true]);
    } else {
        return json_encode(['status' => false]);
    }
}

// Get firebase access token

function getFirebaseAccessToken()
{
    // Try to fetch from cache
    if (Cache::has('firebase_access_token')) {
        return Cache::get('firebase_access_token');
    }

    // Path to your Firebase service account file
    $jsonKeyFile = public_path('assets/firebase/school-siksha-49dcf-firebase-adminsdk-d51yz-db5d364397.json');

    // Scopes required for Firebase Cloud Messaging
    $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

    // Generate credentials
    $credentials = new ServiceAccountCredentials($scopes, $jsonKeyFile);

    // Fetch access token
    $tokenData = $credentials->fetchAuthToken();

    if (!isset($tokenData['access_token'])) {
        throw new \Exception("Failed to generate Firebase access token.");
    }

    $accessToken = $tokenData['access_token'];
    $expiresIn   = $tokenData['expires_in'] ?? 3600;

    // Store token in cache for expiry time (minus a buffer)
    Cache::put('firebase_access_token', $accessToken, now()->addSeconds($expiresIn - 60));

    return $accessToken;
}

