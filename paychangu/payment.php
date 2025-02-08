<?php
require_once('../vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.paychangu.com/mobile-money', [
  'headers' => [
    'accept' => 'application/json',
  ],
]);

$data = json_decode($response->getBody(), true);

// Extract the ref_id for the desired mobile money operator
$mobile_money_operator_ref_id = '';
foreach ($data['data'] as $operator) {
    if ($operator['name'] === 'Airtel Money') { // Change this condition to select the desired operator
        $mobile_money_operator_ref_id = $operator['ref_id'];
        break;
    }
}

$mobile = '0882667288';
$amount = 50;
$charge_id = 'charge_123';

$response = $client->request('POST', 'https://api.paychangu.com/charge', [
    'headers' => [
        'accept' => 'application/json',
        'Content-Type' => 'application/json',
    ],
    'json' => [
        'mobile_money_operator_ref_id' => $mobile_money_operator_ref_id,
        'mobile' => $mobile,
        'amount' => $amount,
        'charge_id' => $charge_id,
    ],
]);

echo $response->getBody();
?>
