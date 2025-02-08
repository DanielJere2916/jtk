<?php
require_once('../vendor/autoload.php');

use GuzzleHttp\Client;

// Get the reference from the URL
$reference = isset($_GET['reference']) ? $_GET['reference'] : '';

if ($reference) {
    $client = new Client();

    try {
        $response = $client->request('GET', 'https://api.paychangu.com/verify-payment', [
            'headers' => [
                'Authorization' => 'Bearer SEC-YUxlHU3ryMe9vLFyvEwDZ6eOgrQxNLYP',
                'accept' => 'application/json',
            ],
            'query' => [
                'reference' => $reference
            ]
        ]);

        $body = $response->getBody();
        $data = json_decode($body, true);

        // Check if the payment was successful
        if ($data['status'] == 'success') {
            // Redirect to cases.php with a success message
            header('Location: ../client/cases.php?status=success');
        } else {
            // Redirect to cases.php with a failure message
            header('Location: ../client/cases.php?status=failure');
        }
    } catch (Exception $e) {
        // Redirect to cases.php with an error message
        header('Location: ../client/cases.php?status=error');
    }
} else {
    // Redirect to cases.php with a missing reference message
    header('Location: ../client/cases.php?status=missing_reference');
}
exit();
?>