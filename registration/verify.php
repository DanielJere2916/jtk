<?php 
include('../auth/connection.php');
$verification_status = null;

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['code'])) {
    try {
        $verification_code = mysqli_real_escape_string($conn, $_GET['code']);
        
        $sql = "UPDATE users SET verified_at = NOW(),role='client'
                WHERE verification_code = ? AND verified_at IS NULL";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $verification_code);
        mysqli_stmt_execute($stmt);
        
        if (mysqli_affected_rows($conn) > 0) {
            $verification_status = [
                'status' => 'success',
                'message' => 'Email verified successfully! You can now login.',
                'title' => 'Success!'
            ];
        } else {
            throw new Exception('Invalid verification code or email already verified.');
        }
    } catch (Exception $e) {
        $verification_status = [
            'status' => 'error',
            'message' => $e->getMessage(),
            'title' => 'Error!'
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verification - Legal Services</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/font.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f6fa;
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

        .checkmark {
            width: 80px;
            height: 80px;
        }

        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #4CAF50;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            stroke: #4CAF50;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }

        @keyframes stroke {
            100% { stroke-dashoffset: 0; }
        }

        @keyframes modalSlideIn {
            from { transform: translateY(-100px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-close {
            margin-top: 20px;
            padding: 10px 30px;
            border: none;
            background: #4CAF50;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .modal-close:hover {
            background: #45a049;
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
            <button class="modal-close" onclick="closeModal()">Continue to Login</button>
        </div>
    </div>

    <script>
        function showModal(response) {
            const modal = document.getElementById('responseModal');
            const title = document.getElementById('modalTitle');
            const message = document.getElementById('modalMessage');
            
            title.textContent = response.title;
            message.textContent = response.message;
            
            if(response.status === 'error') {
                document.querySelector('.checkmark__circle').style.stroke = '#ff3333';
                document.querySelector('.checkmark__check').style.stroke = '#ff3333';
            }
            
            modal.style.display = 'block';
        }

        function closeModal() {
            const modal = document.getElementById('responseModal');
            modal.style.display = 'none';
            window.location.href = '../login/login.php';
        }

        // Show modal on page load if verification status exists
        <?php if ($verification_status): ?>
            showModal(<?php echo json_encode($verification_status); ?>);
        <?php endif; ?>

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