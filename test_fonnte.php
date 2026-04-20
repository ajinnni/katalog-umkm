<?php
$token  = 'aFUCu1xfixcmZjDD3XS6'; // ganti dengan token asli
$target = '6283824032436';          // ganti nomor WA kamu sendiri

$ch = curl_init('https://api.fonnte.com/send');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER     => ['Authorization: ' . $token],
    CURLOPT_POSTFIELDS     => [
        'target'  => $target,
        'message' => 'Test OTP: 123456',
    ],
]);
$response = curl_exec($ch);
$error    = curl_error($ch);
curl_close($ch);

echo '<pre>';
echo 'Response: ' . $response . "\n";
echo 'cURL Error: ' . $error . "\n";
echo '</pre>';