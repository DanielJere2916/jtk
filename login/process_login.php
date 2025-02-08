<?php
session_start();
require '../auth/connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        // Get user data
        $sql = "SELECT u.user_id, u.email, u.password, u.verified_at, u.role, p.first_name, p.last_name 
                FROM users u 
                LEFT JOIN user_profiles p ON u.user_id = p.user_id 
                WHERE u.email = ?";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Invalid credentials';
            header('Location: login.php');
            exit();
        }

        if ($user['verified_at'] === null) {
            $_SESSION['error'] = 'Please verify your email before logging in';
            header('Location: login.php');
            exit();
        }

        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['profile_image']= $user['photo'];
        $_SESSION['phone']= $user['phone'];

        // Redirect to dashboard
        header('Location: ../client/dashboard.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = 'An error occurred. Please try again.';
        header('Location: login.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'Invalid request method';
    header('Location: login.php');
    exit();
}
?>