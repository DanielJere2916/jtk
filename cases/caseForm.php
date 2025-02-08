<?php
require '../auth/connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');
    try {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        
        // Check if email exists
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            throw new Exception("Email address already registered");
        }

        mysqli_begin_transaction($conn);

        // 1. Insert user first
        $password = password_hash($_POST['psw'], PASSWORD_DEFAULT);
        $verification_code = bin2hex(random_bytes(16));

        $sql1 = "INSERT INTO users (email, password, verification_code) VALUES (?, ?, ?)";
        $stmt1 = mysqli_prepare($conn, $sql1);
        mysqli_stmt_bind_param($stmt1, "sss", $email, $password, $verification_code);
        mysqli_stmt_execute($stmt1);
        
        // 2. Get the user ID
        $user_id = mysqli_insert_id($conn);

        // 3. Insert client details
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);

        $sql2 = "INSERT INTO clients (user_id, fname, lname, phone) VALUES (?, ?, ?, ?)";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "isss", $user_id, $fname, $lname, $phone);
        mysqli_stmt_execute($stmt2);

        // 4. Send verification email
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'chirwawalusungu@gmail.com';
        $mail->Password = 'pgpbojqozylbymvl';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('chirwawalusungu@gmail.com', 'John Tennyson, Kawelo & Associates');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = "Please click the following link to verify your email: <br>
                      http://localhost/school/registration/verify.php?code=$verification_code";
        
        $mail->send();
        mysqli_commit($conn);

        echo json_encode([
            'status' => 'success',
            'message' => 'Registration successful! Please check your email to verify your account.',
            'title' => 'Success!'
        ]);
        exit;

    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($conn);
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Email address already exists'
        ]);
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - John Tennyson, Kawelo & Associates</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body *{
            font-family: 'Open Sans', sans-serif;
            margin: 0;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
        }
        /* Center the image and position the close button */
        .imgcontainer {
            text-align: center;
            margin: 24px 0 12px 0;
            position: relative;
        }

        img.avatar {
            width: 20%;
            border-radius: 20%;
        }

        /* Add padding to containers */
        .container {
            padding: 16px;
            background-color: white;
        }

        /* Full-width input fields */
        input[type=text], input[type=password], input[type=tel] {
            width: 95%;
            padding: 12px;
            margin: 5px 0;
            display: block;
            border: none;
            background: #f1f1f1;
        }

        input[type=text]:focus, input[type=password]:focus {
            background-color: #ddd;
            outline: none;
        }

        /* Overwrite default styles of hr */
        hr {
            border: 1px solid #f1f1f1;
            margin-bottom: 25px;
        }

        /* Set a style for the submit button */
        .registerbtn {
            background-color: rgba(194, 144, 70, 0.78);
            color: white;
            padding: 16px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
            opacity: 0.9;
        }

        .registerbtn:hover {
            opacity: 3;
        }

        /* Add a blue text color to links */
        a {
            color: dodgerblue;
        }

        /* Set a grey background color and center the text of the "sign in" section */
        .signin {
            background-color: #f1f1f1;
            text-align: center;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        @media screen and (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .modal-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 400px;
            margin: 20vh auto;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            animation: modalSlideIn 0.3s ease-out;
        }

        .modal-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
        }

        .checkmark__circle {
            stroke: #4CAF50;
            stroke-width: 2;
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark__check {
            stroke: #4CAF50;
            stroke-width: 2;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }

        @keyframes modalSlideIn {
            from { transform: translateY(-100px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes stroke {
            100% { stroke-dashoffset: 0; }
        }
    </style>
</head>
<body>
<div class="modal" id="responseModal">
    <div class="modal-content">
        <div class="modal-icon">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>
        <h2 id="modalTitle"></h2>
        <p id="modalMessage"></p>
        <button class="modal-close" onclick="closeModal()">Continue</button>
    </div>
</div>

<form id="registrationForm" method="post">
    <div class="imgcontainer">
        <img src="../images/logo.png" alt="Avatar" class="avatar">
    </div>
    <div class="container">
        <h1>Case Form</h1>
        <p>Please fill in this form to open a case.</p>
        <hr>
        
        <div class="form-grid">
            <div class="form-group">
                <label for="fname"><b>Legal First Name</b></label>
                <input type="text" placeholder="Enter Legal First Name" name="fname" id="fname" required>
            </div>
            
            <div class="form-group">
                <label for="lname"><b>Legal Last Name</b></label>
                <input type="text" placeholder="Enter Legal Last Name" name="lname" id="lname" required>
            </div>
            
            <div class="form-group">
                <label for="email"><b>Case Name</b></label>
                <input type="text" placeholder="Enter Email" name="email" id="email" required>
            </div>
            
            <div class="form-group">
                <label for="phone"><b>Case Type</b></label>
                <input type="tel" placeholder="Enter Phone Number" name="phone" id="phone" required>
            </div>
            
            <div class="form-group">
                <label for="psw"><b>Relevant Files</b></label>
                <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
            </div>
            
            <!-- <div class="form-group">
                <label for="psw-repeat"><b>Repeat Password</b></label>
                <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" required>
            </div> -->
        </div>

        <hr>
        <button type="submit" class="registerbtn">Submit</button>
    </div>

</form>
<script>
$(document).ready(function() {
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault();
        
        // Password validation
        const password = $('#psw').val();
        const confirmPassword = $('#psw-repeat').val();
        
        if (password.length < 8) {
            showModal({
                status: 'error',
                message: 'Password must be at least 8 characters long'
            });
            return;
        }
        
        if (password !== confirmPassword) {
            showModal({
                status: 'error',
                message: 'Passwords do not match'
            });
            return;
        }

        $.ajax({
            type: 'POST',
            url: '',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                showModal(response);
            },
            error: function(xhr) {
                try {
                    showModal(JSON.parse(xhr.responseText));
                } catch(e) {
                    showModal({
                        status: 'error',
                        message: 'An unexpected error occurred'
                    });
                }
            }
        });
    });
});

function showModal(response) {
    const modal = document.getElementById('responseModal');
    const title = document.getElementById('modalTitle');
    const message = document.getElementById('modalMessage');
    
    title.textContent = response.title || (response.status === 'success' ? 'Success!' : 'Error');
    message.textContent = response.message;
    
    if(response.status === 'error') {
        document.querySelector('.checkmark__circle').style.stroke = '#ff3333';
        document.querySelector('.checkmark__check').style.stroke = '#ff3333';
    } else {
        document.querySelector('.checkmark__circle').style.stroke = '#4CAF50';
        document.querySelector('.checkmark__check').style.stroke = '#4CAF50';
    }
    
    modal.style.display = 'block';
}

function closeModal() {
    const modal = document.getElementById('responseModal');
    modal.style.display = 'none';
    
    if(document.getElementById('modalTitle').textContent === 'Success!') {
        window.location.href = '../login/login.php';
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('responseModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

</body>
</html>
