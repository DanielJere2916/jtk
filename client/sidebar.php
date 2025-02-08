<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" type="text/css" href="../assets/css/font.css">
<style>
    
body {
    margin: 0;
     padding: 0;
    font-family: "Manrope", serif;
  font-optical-sizing: auto;
  font-weight: 500;
  font-style: normal;
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
    width: 150px;
    height: 90px;
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
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.service-card {
    background: #333;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(92, 91, 91, 0.8);
}
.service-card h3, p{
    color: white;
}
.nav-icon img {
  width: 38px; /* Set the width of the icon */
  height: 38px; /* Set the height of the icon */
}
.nav-icon {
  display: inline-block;
  vertical-align: middle;
    margin-right: 10px; /* Space between icon and text */
}

.table-container {
  margin-top: 20px;
  background-color: white; /* White background for the table container */
  padding: 20px;
  border-radius: 8px; /* Rounded corners */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

table {
  width: 100%;
  border-collapse: collapse;
}

table, th, td {
  border: 1px solid #ddd;
}

th, td {
  padding: 8px;
  text-align: left;
}

th {
  background-color: #f2f2f2;
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
</style>
</head>
<body>


<div class="sidebar">
    <div class="logo-container">
        <img src="../images/logo.png" alt="Legal Firm Logo">
        <!-- <h4>MANAGEMENT SYSTEM</h4> -->
    </div>
    <a  class="active" href="#dashboard"><i class="fas fa-home"></i> Dashboard</a>
    <a href="#appointments"><i class="fas fa-calendar-alt"></i> Appointments</a>
    <a href="cases.php"> <i class="fas fa-briefcase"></i> My Cases</a>
    <a href="#documents"><i class="fas fa-file-alt"></i> Documents</a>
    <a href="#messages"><i class="fas fa-envelope"></i> Reports</a>
    <a href="payments.php"><i class="fas fa-credit-card"></i> Payments</a>
    <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
    <a href="../auth/logout.php" class="logout-link">
        <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="content">
        <div class="welcome-section">

        <div class="welcome-section">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="welcome-header"><i class="nav-icon"><img src="../assets/fonts/greet.png" alt="greet"></i> Hi, <?php echo isset($_SESSION['first_name']) && isset($_SESSION['last_name']) ? $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] : ''; ?></h3>
            <div class="profile-image">
                <img src="<?php echo isset($_SESSION['profile_image']) ? '../images/' . $_SESSION['profile_image'] : '../images/default.png'; ?>" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%;">
        
            </div>
        </div>
    </div>
        <!-- <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="welcome-header"><i class="nav-icon"><img src="../assets/fonts/greet.png" alt="greet"></i> Hi, <?php echo isset($_SESSION['first_name']) && isset($_SESSION['last_name']) ? $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] : ''; ?></h3>
            <div class="profile-image">
            </div>
        </div> -->
        
        <div class="services-grid">
            <div class="service-card">
                <h3><i class="nav-icon"><img src="../assets/fonts/book.png" alt="greet"></i> Next Appointment</h3><hr>
                <p>No upcoming appointments</p>
            </div>
            <div class="service-card">
                <h3><i class="nav-icon"><img src="../assets/fonts/case.png" alt="greet"></i> Active Cases</h3><hr>
            <?php
            include '../auth/connection.php';

            $user_id = $_SESSION['user_id'];
            $sql = "SELECT COUNT(*) as total_cases FROM cases WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $total_cases = $row['total_cases'];
            echo "<h3 style='text-align:center; '>" . $total_cases . "</h3>";

            $stmt->close();
            // $conn->close();
            ?>
            </div>
            <div class="service-card">
                <h3><i class="nav-icon"><img src="../assets/fonts/not.png" alt="greet"></i> Notifications</h3><hr>
                <p>Stay updated with case progress</p>
            </div>
        </div>
    </div>

    <div class="table-container">
        <h2>Recent Cases</h2>
        <table>
            <thead>
                <tr>
                    <th>SN</th>
                    <th>STATUS</th>
                    <th>NAME</th>
                    <th>TYPE</th>
                    <th>Ref NUMBER</th>
                    <th>DATE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../auth/connection.php';

                
            // Define status colors
            $status_colors = [
                'Open' => 'green',
                'Closed' => 'red',
                'Pending' => 'orange',
                'In Progress' => 'blue'
              ];
  

                $user_id = $_SESSION['user_id'];

                // Fetch recent 5 cases for the logged-in user
                $sql = "SELECT case_id, case_name, status, case_type, case_number, created_at FROM cases WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["case_id"] . "</td>
                                <td style='background-color: " . $status_colors[$row["status"]] . "; border-radius:20%; '>" . $row["status"] . "</td>
                                <td>" . $row["case_name"] . "</td>
                                <td>" . $row["case_type"] . "</td>
                                <td>" . $row["case_number"] . "</td>
                                <td>" . $row["created_at"] . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No recent cases found</td></tr>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
