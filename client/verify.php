<?php
require '../auth/check_auth.php';
require_once('../vendor/autoload.php');


use GuzzleHttp\Client;


$reference = isset($_SESSION['reference']) ? $_SESSION['reference'] : 'JTK/000/000';
function generateReference() {
    $prefix = 'JTK';
    $middle = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
    $suffix = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
    return "$prefix/$middle/$suffix";
}

$reference = isset($_SESSION['reference']) ? $_SESSION['reference'] : generateReference();
$fname = isset($_SESSION['fname']) ? $_SESSION['fname'] : '';
$lname = isset($_SESSION['lname']) ? $_SESSION['lname'] : '';
$category = isset($_SESSION['category']) ? $_SESSION['category'] : '';

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
            // Insert payment record into the database
            $payment_sql = "INSERT INTO payments (user_id, amount, payment_date, payment_method) VALUES (?, ?,NOW(), ?)";
            $stmt = $conn->prepare($payment_sql);
            $amount = $data['data']['amount']; 
            $method = $data['data']['type'];
            $stmt->bind_param("sss", $reference, $amount, $method);
            $stmt->execute();
            $stmt->close();

            // Insert collected data into the cases table
            $case_sql = "INSERT INTO cases (case_name, status, case_type, case_number, created_at, user_id, case_details) VALUES (?, ?, ?, ?, NOW(), ?, ?)";
            $stmt = $conn->prepare($case_sql);
            $case_name = $fname . ' ' . $lname . ' - ' . $category;
            $case_status = "Open"; // Ensure this status is one of the ENUM values defined in the database
            // Define status colors
            $status_colors = [
                'Open' => 'green',
                'Closed' => 'red',
                'Pending' => 'orange',
                'In Progress' => 'blue'
            ];

            // Fetch the color for the current status
            $case_status_color = isset($status_colors[$case_status]) ? $status_colors[$case_status] : 'black';
            $case_type = $category;
            $case_number = $reference;
            $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
            $case_details = "Consultation Fee"; // Replace with actual case details if available
            $stmt->bind_param("ssssss", $case_name, $case_status, $case_type, $case_number, $user_id, $case_details);
            $stmt->execute();
            $stmt->close();
    
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