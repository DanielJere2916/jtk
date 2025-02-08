<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
<script src="https://in.paychangu.com/js/popup.js"></script>

<style>
body * {
    font-family: 'Open Sans', sans-serif;
    margin: 0;
    box-sizing: border-box;
}

.sidebar {
    margin: 0;
    padding: 20px 0;
    width: 250px;
    background-color:#333;
    position: fixed;
    height: 100%;
    overflow: auto;
    color: white;
}

.logo-container {
    text-align: center;
    padding: 20px;
    margin-bottom: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.logo-container img {
    width: 120px;
    border-radius: 10px;
    margin-bottom: 10px;
}

.sidebar a {
    display: flex;
    align-items: center;
    color: #ecf0f1;
    padding: 16px 25px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.sidebar a i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.sidebar a.active {
    background-color:rgba(194, 144, 70, 0.78);
    color: white;
}

.sidebar a:hover:not(.active) {
    background-color: #34495e;
    color: white;
}

.content {
    margin-left: 250px;
    padding: 40px;
    height: 100vh;
    background-color: #f5f6fa;
}

.welcome-section {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.welcome-header {
    color: #2c3e50;
    margin-bottom: 20px;
}
.services-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
}

.service-card {
    background: #333;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.primary-card {
    background: #f8f9fe;
}

.quick-actions {
    margin: 20px 0;
}

.action-btn {
    padding: 10px 20px;
    margin-right: 10px;
    border: none;
    border-radius: 5px;
    background: #007bff;
    color: white;
    cursor: pointer;
}

.case-item, .appointment-item, .update-item {
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.case-status {
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 12px;
    background: #e3e3e3;
}

.update-item.high {
    border-left: 3px solid #ff4444;
}

.view-details {
    color: #007bff;
    text-decoration: none;
    font-size: 14px;
}

@media screen and (max-width: 700px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }
    .sidebar a {float: left;}
    .content {margin-left: 0;}
}

@media screen and (max-width: 400px) {
    .sidebar a {
        text-align: center;
        float: none;
    }
}

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

/* Extra styles for the cancel button */
.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
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

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  padding-top: 60px;
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button (x) */
.close {
  position: absolute;
  right: 25px;
  top: 0;
  color: #000;
  font-size: 35px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: red;
  cursor: pointer;
}

/* Add Zoom Animation */
.animate {
  -webkit-animation: animatezoom 0.6s;
  animation: animatezoom 0.6s
}

@-webkit-keyframes animatezoom {
  from {-webkit-transform: scale(0)} 
  to {-webkit-transform: scale(1)}
}
  
@keyframes animatezoom {
  from {transform: scale(0)} 
  to {transform: scale(1)}
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}

</style>
</head>
<body>
    <?php

    include('../auth/connection.php');
    
    // Prepare and execute query with prepared statement
    $sql = "SELECT id, name, amount FROM service";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store services in array for JavaScript use
    $services = array();
    while($row = $result->fetch_assoc()) {
        $services[] = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'amount' => $row['amount']
        );
    }

    // At the top of file, after database connection
    $consultation_sql = "SELECT id FROM service WHERE name='Consultation Fee' LIMIT 1";
    $consultation_result = $conn->query($consultation_sql);
    $consultation_service = $consultation_result->fetch_assoc();
    $consultation_id = $consultation_service['id'];
    ?>
<div id="wrapper"></div>

<script>
    // Pass PHP array to JavaScript
    const services = <?php echo json_encode($services); ?>;

    function makePayment(serviceId) {
        // Find service details by ID
        const service = services.find(s => s.id === serviceId);
        
        if (!service) {
            console.error('Service not found');
            return;
        }

        PaychanguCheckout({
            "public_key": "PUB-TEST-qxTNSATgXWv690NScYv7Ptr5bxRa6FrF",
            "tx_ref": '' + Math.floor((Math.random() * 1000000000) + 1),
            "amount": service.amount,
            "currency": "MWK",
            "callback_url": "https://developer.paychangu.com/docs/inline-popup", 
            "return_url": "",
            "customer": {
                "email": "<?php echo $_SESSION['email'] ?? 'yourmail@mail.com'; ?>",
                "first_name": "<?php echo $_SESSION['name'] ?? 'Mac'; ?>",
                "last_name": "<?php echo $_SESSION['last_name'] ?? 'Phiri'; ?>",
            },
            "customization": {
                "title": service.name + " Payment",
                "description": "Payment for " + service.name,
            },
            "meta": {
                "item_id": service.id,
                "response": "Response"
            }
        });
    }
</script>

<div class="sidebar">
    <div class="logo-container">
        <img src="../images/logo.png" alt="Legal Firm Logo">
        <h5>MANAGEMENT SYSTEM</h5>
    </div>
    <a href="#dashboard"><i class="fas fa-home"></i>Dashboard</a>
    <a href="#appointments"><i class="fas fa-calendar-alt"></i>Appointments</a>
    <a class="active" href="#cases"><i class="fas fa-briefcase"></i>My Cases</a>
    <a href="#documents"><i class="fas fa-file-alt"></i>Documents</a>
    <a href="#messages"><i class="fas fa-envelope"></i>Messages</a>
    <a href="#payments"><i class="fas fa-credit-card"></i>Payments</a>
    <a href="#profile"><i class="fas fa-user"></i>Profile</a>
    <a href="../auth/logout.php" class="logout-link">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
</div>

<div class="content">
    <div class="welcome-section">
        <!-- <h1 class="welcome-header">Welcome, <?php echo $_SESSION['name']; ?></h1> -->
        <p class="welcome-subtitle">Your Legal Dashboard</p>

        <div class="quick-actions">
            <button class="action-btn" onclick="document.getElementById('id01').style.display='block'" style="width:auto;"><i class="fas fa-plus"></i> Start New Case</button>
            <button class="action-btn"><i class="fas fa-file-upload"></i> Track Case</button>
        </div>
        
        <div class="services-grid">
            <!-- Active Cases Overview -->
            <div class="service-card primary-card">
                <h3><i class="fas fa-gavel"></i> Your Active Cases</h3>
                <div class="case-list">
                    <?php if(isset($activeCases) && count($activeCases) > 0): ?>
                        <?php foreach($activeCases as $case): ?>
                            <div class="case-item">
                                <span class="case-number">Case #<?php echo $case['id']; ?></span>
                                <span class="case-status <?php echo $case['status']; ?>"><?php echo $case['status']; ?></span>
                                <p class="case-title"><?php echo $case['title']; ?></p>
                                <a href="case-details.php?id=<?php echo $case['id']; ?>" class="view-details">View Details →</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No active cases at the moment</p>
                        <a href="new-case.php" class="start-case-link">Start your first case</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="service-card">
                <h3><i class="fas fa-calendar-check"></i> Upcoming Appointments</h3>
                <div class="appointment-list">
                    <?php if(isset($appointments) && count($appointments) > 0): ?>
                        <?php foreach($appointments as $apt): ?>
                            <div class="appointment-item">
                                <div class="apt-date"><?php echo date('M d, Y', strtotime($apt['date'])); ?></div>
                                <div class="apt-time"><?php echo date('h:i A', strtotime($apt['time'])); ?></div>
                                <div class="apt-type"><?php echo $apt['type']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No upcoming appointments</p>
                        <button class="schedule-btn">Schedule Consultation</button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Important Updates -->
            <div class="service-card">
                <h3><i class="fas fa-bell"></i> Recent Updates</h3>
                <div class="updates-list">
                    <?php if(isset($notifications) && count($notifications) > 0): ?>
                        <?php foreach($notifications as $notif): ?>
                            <div class="update-item <?php echo $notif['priority']; ?>">
                                <span class="update-time"><?php echo $notif['time']; ?></span>
                                <p class="update-message"><?php echo $notif['message']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No new updates</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="id01" class="modal">
  <div class="modal-content animate">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
      <img src="../images/logo.png" alt="Avatar" class="avatar">
    </div>

    <div class="container">
      <label for="email"><b>Consultation Fees Required</b></label>
      <input type="text" placeholder="50 MWK Consultation Fee is required to start a new case" readonly required>     
      <button class="action-btn" type="button" id="start-payment-button" onClick="makePayment(<?php echo $consultation_id; ?>)">Pay Now</button>
    </div>

    <div class="container" style="background-color:#f1f1f1">
      <button class="action-btn" type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
    </div>
  </div>
</div>

<script>
// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

</script>

</body>
</html>
