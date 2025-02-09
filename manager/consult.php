<?php

require '../auth/check_auth.php';

// Fetch fee from consultation table
$fee_sql = "SELECT amount FROM consultation";
$stmt = $conn->prepare($fee_sql);
$stmt->execute();
$stmt->bind_result($fee);
$stmt->fetch();
$stmt->close();

// Get the other parameters from the URL
$fname = isset($_GET['fname']) ? $_GET['fname'] : '';
$lname = isset($_GET['lname']) ? $_GET['lname'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$case_files = isset($_FILES['case_files']) ? $_FILES['case_files'] : array();

// Store the parameters in the session for later use
$_SESSION['fname'] = $fname;
$_SESSION['lname'] = $lname;
$_SESSION['category'] = $category;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://in.paychangu.com/js/popup.js"></script>
    <style>
        
body {
    font-size: 14px;
    font-family: "Manrope", serif;
    font-weight: 400;
    color: #333;
}

#start-payment-button {
    cursor: pointer;
    position: relative;
    background-color: #02b9fd;
    color: #12122c;
    max-width: 30%;
    padding: 7.5px 16px;
    font-weight: 500;
    font-size: 14px;
    border-radius: 4px;
    border: none;
    transition: all .1s ease-in;
    vertical-align: middle;
}

    </style>
</head>
<body>
<div id="wrapper"></div>
<!-- Removed the button as the payment will be triggered automatically -->
    
</body>
</html>
<script>
    function makePayment(){
        PaychanguCheckout({
            "public_key": "PUB-zpcElqNuV3QO3iKTZDYemjJno26MAQ1K",
            "tx_ref": '' + Math.floor((Math.random() * 1000000000) + 1),
            "amount": <?php echo $fee; ?>, // Display the fetched fee
            "currency": "MWK",
            "callback_url": "http://localhost/jtk/client/verify.php",
            "return_url": "",
            "customer": {
                "email": "<?php echo $_SESSION['email']; ?>",
                "first_name": "<?php echo $_SESSION['first_name']; ?>",
                "last_name": "<?php echo $_SESSION['last_name']; ?>",
            },
            "customization": {
                "title": "Consultation fee",
                "description": "Payment Description",
            },
            "meta": {
                "uuid": "uuid",
                "response": "Response"
            }
        });
    }

    // Automatically call the makePayment function when the page loads
    window.onload = function() {
        makePayment();
    }
</script>
