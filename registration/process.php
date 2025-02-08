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
        $check_sql = "SELECT user_id FROM users WHERE email = ?";
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

        $sql1 = "INSERT INTO users (email, password, verification_code, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
        $stmt1 = mysqli_prepare($conn, $sql1);
        mysqli_stmt_bind_param($stmt1, "sss", $email, $password, $verification_code);
        mysqli_stmt_execute($stmt1);
        
        // 2. Get the user ID
        $user_id = mysqli_insert_id($conn);

        // 3. Handle photo upload
        $photo = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $photo = 'uploads/' . basename($_FILES['photo']['name']);
            move_uploaded_file($_FILES['photo']['tmp_name'], '../' . $photo);
        }

        // 4. Insert user profile details
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $state = mysqli_real_escape_string($conn, $_POST['state']);
        $zip = mysqli_real_escape_string($conn, $_POST['zip']);

        $sql2 = "INSERT INTO user_profiles (user_id, first_name, last_name, phone, address, city, state, zip, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "issssssss", $user_id, $first_name, $last_name, $phone, $address, $city, $state, $zip, $photo);
        mysqli_stmt_execute($stmt2);

        // 5. Send verification email
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
                      http://localhost/jtk/registration/verify.php?code=$verification_code";
        
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
