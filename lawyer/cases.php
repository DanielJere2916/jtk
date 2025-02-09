<?php

 require '../auth/check_auth.php';

// Fetch fee from consultation table
$fee_sql = "SELECT amount FROM consultation";
$stmt = $conn->prepare($fee_sql);
$stmt->execute();
$stmt->bind_result($fee);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=waving_hand" />
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
    padding: 20px;
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

* {
  box-sizing: border-box;
}


#regForm {
  background-color: #ffffff;
  margin: 100px auto;
  font-family: "Manrope", serif;
  padding: 0px;
  width: 40%;
  min-width: 300px;
}

h1 {
  text-align: center;  
}

input,select {
  padding: 10px;
  width: 100%;
  font-size: 17px;
  font-family: Raleway;
  border: 1px solid #aaaaaa;
}

/* Mark input boxes that gets an error on validation: */
input.invalid {
  background-color: #ffdddd;
}

/* Hide all steps by default: */
.tab {
  display: none;
}

button {
  background-color: #04AA6D;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  font-family: Raleway;
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#prevBtn {
  background-color: #bbbbbb;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;  
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #04AA6D;
}

/* The Modal (background) */
.modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
    padding-top: 0px; 
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: 5% auto; 
    padding: 0;
    border: 1px solid #888;
    width: 50%; 
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    animation: fadeIn 0.5s;
}

/* The Close Button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

@keyframes fadeIn {
    from {opacity: 0;}
    to {opacity: 1;}
}
</style>
</head>
<body>


<div class="sidebar">
    <div class="logo-container">
        <img src="../images/logo.png" alt="Legal Firm Logo">
        <!-- <h3>MANAGEMENT SYSTEM</h3> -->
    </div>
    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="#appointments"><i class="fas fa-calendar-alt"></i> Appointments</a>
    <a class="active" href="cases.php"> <i class="fas fa-briefcase"></i> My Cases</a>
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
        <h3 class="welcome-header"><i class="nav-icon"><img src="../assets/fonts/warning.png" alt="greet"></i>ATTENTION !! </h3>
        <p style="color:black;">You will be charged <strong><?php echo isset($fee) ? 'MWK ' . number_format($fee, 2) : 'N/A'; ?> </strong> per case as the consultation fee</p>
        <button id="openModalBtn" style="margin-top: 20px; padding: 10px 20px; background-color: #333; color: white; border: none; border-radius: 5px; cursor: pointer;">Start New Case</button>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <!-- <h2>Create New Case</h2> -->
            <form id="regForm" action="consult.php">
  <h1>Case Collection Form</h1>
  <!-- One "tab" for each step in the form: -->
<div class="tab"><strong>Names</strong>
    <p><input readonly oninput="this.className = ''" name="fname" value="<?php echo isset($_SESSION['first_name']) ? $_SESSION['first_name'] : ''; ?>"></p>
    <p><input readonly oninput="this.className = ''" name="lname" value="<?php echo isset($_SESSION['last_name']) ? $_SESSION['last_name'] : ''; ?>"></p>
</div>
<div class="tab"><strong>Case Type</strong>
    <p>
        <select name="category" oninput="this.className = ''">
            <option value="">Select Type of Case...</option>
            <option value="Family Law">Notary Public</option>
            <option value="Criminal Law">Commissioners for Oaths</option>
            <option value="Corporate Law">Trademark and Patent Agents</option>
            <option value="Civil Rights">Conveyancers</option>
            <option value="Immigration Law">Mediation and Conflict Resolution</option>
        </select>
    </p>
</div>
<div class="tab"><strong>Case Name</strong>
    <p><input placeholder="Suggested" oninput="this.className = ''" name="uname"></p>
  </div>
  <div class="tab"><strong>Any Relevant File</strong>
    <p><input type="file" name="case_files[]" multiple oninput="this.className = ''"></p>
  </div>

  <div style="overflow:auto;">
    <div style="float:right;">
      <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
      <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
    </div>
  </div>
  <!-- Circles which indicates the steps of the form: -->
  <div style="text-align:center;margin-top:40px;">
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
  </div>
</form>
        </div>
    </div>

    <script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("openModalBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Submit";
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  //... and run a function that will display the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:
    document.getElementById("regForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false
      valid = false;
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}
</script>

    <div class="table-container">
        <h2>Cases</h2>
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

            // Fetch recent 5 cases for the logged in user
            $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
            $sql = "SELECT case_id, case_name, status, case_type, case_number, created_at FROM cases WHERE user_id = ? ORDER BY created_at ASC LIMIT 5";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
              // Output data of each row
              while($row = $result->fetch_assoc()) {
            $status_color = isset($status_colors[$row["status"]]) ? $status_colors[$row["status"]] : 'black';
            echo "<tr>
              <td>" . $row["case_id"] . "</td>
              <td style='background-color: " . $status_color . "; border-radius:20%; '>" . $row["status"] . "</td>
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
