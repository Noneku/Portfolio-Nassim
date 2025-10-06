<?php
function postMailToAPI($name, $subject, $body, $from_email, $to_email) {
    $apiToken = $_ENV['PORTALPRO_API_KEY'];
    $apiUrl   = $_ENV['APP_URL'] . '/api/emails';

    $data = [
        'name'       => $name,
        'subject'    => $subject,
        'body'       => $body,
        'from_email' => $from_email,
        'to_email'   => $to_email
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiToken,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
