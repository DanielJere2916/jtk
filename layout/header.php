<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Lexend:wght@200&display=swap');
</style>
<style>

body {
  margin: 0;
  font-family: "Lexend", serif;
  font-optical-sizing: auto;
  font-weight: 200;
  font-style: normal;
}

.navbar {
  overflow: hidden;
  background-color: #333;
  display: flex;
  justify-content: space-between; /* Space between items */
  align-items: center; /* Center items vertically */
  padding: 0 16px; /* Add padding to the sides */
}

.navbar .nav-items {
  display: flex;
  justify-content: center; /* Center the content */
  flex-grow: 1; /* Allow it to grow to take available space */
}

.navbar a {
  font-size: 16px;
  font-weight: bold;
  color: white;
  text-align: center;
  padding: 8px 16px;
  text-decoration: none;
}

.subnav {
  overflow: hidden;
}

.subnav .nav-icon {
  display: inline-block;
  vertical-align: middle;
  margin-right: 10px; /* Space between icon and text */
}

.subnav .nav-icon img {
  width: 24px; /* Set the width of the icon */
  height: 28px; /* Set the height of the icon */
}

.subnav .subnavbtn {
  font-size: 16px;
  font-weight: bold;
  border: none;
  outline: none;
  color: white;
  padding: 14px 16px;
  background-color: inherit;
  font-family: inherit;
  margin: 1;
  display: inline-flex;
  align-items: center; /* Center icon and text vertically */
}

.navbar a:hover, .subnav:hover .subnavbtn {
  background-color: rgba(194, 144, 70, 0.78);
  color: #fff; /* Change text color to white */
  transform: scale(1.08); /* Slightly enlarge the element */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add a subtle shadow */
  transition: all 0.5s ease; /* Smooth transition for all properties */
}

.subnav-content {
  display: none;
  position: absolute;
  left: 0;
  background-color: rgba(194, 144, 70, 0.78);
  width: 100%;
  z-index: 1;
}

.subnav-content a {
  color: white;
  text-decoration: none;
}

.subnav-content a:hover {
  background-color: #eee;
  color: black;
}

.subnav:hover .subnav-content {
  display: block;
}

/* Media query for smaller screens */
@media screen and (max-width: 768px) {
  .navbar {
    flex-direction: column; /* Stack items vertically */
    align-items: flex-start; /* Align items to the start */
  }

  .navbar .nav-items {
    justify-content: flex-start; /* Align items to the start */
  }

  .navbar .button {
    align-self: flex-end; /* Align button to the end */
  }
}
</style>
</head>
<body>

<div class="navbar">


  <a href="#home">
 
<h2><span style="font-weight:50px;">J</span><img src="./assets/fonts/logo.png" alt="Logo" style="width: 55px; height: 50px; border-radius: 50%;">K</h2> 
<h5 style="font-weight:50px;">MANAGEMENT SYSTEM</h5>
  </a>
  <div class="nav-items">
    <div class="subnav">
      <button class="subnavbtn"><i class="nav-icon"><img src="./assets/fonts/service.png" alt="Service Image"></i> Services </button>
      <div class="subnav-content">
        <a href="#bring">Notary Public</a>
        <a href="#deliver">Commissioners for Oaths</a>
        <a href="#package">Trademark and Patent Agents</a>
        <a href="#express">Conveyancers</a>
        <a href="#express">Mediation and Conflict Resolution</a>
      </div>
    </div> 

    <div class="subnav">
      <button class="subnavbtn"><i class="nav-icon"><img src="./assets/fonts/pay.png" alt="Service Image"></i> Pricing </button>
    </div> 
    
    <div class="subnav">
    <button class="subnavbtn"><i class="nav-icon"><img src="./assets/fonts/firm.png" alt="firm Image"></i> Firm </button>
      <div class="subnav-content">
        <a href="#company">About</a>
        <a href="#team">Carriers</a>
        <a href="#careers">Blogs</a>
      </div>
    </div> 
    <div class="subnav">
    <button class="subnavbtn"><i class="nav-icon"><img src="./assets/fonts/law.png" alt="Law Image"></i> Lawyers </button>
      <div class="subnav-content">
        <a href="#link1"><strong>Victor Charles Gondwe</strong><span style="color: black;"> LLB (Hons) Mw</span></a>
        <a href="#link2"><strong>Wesley Kawelo Chalo Mwafulirwa</strong><span style="color: black;"> LLB (Hons) Mw, MA (Austria)</span></a>
        <a href="#link3"><strong>William Mtchayabweka Chibwe Jr</strong><span style="color: black;"> LLB (Hons) Mw</span></a>
        <a href="#link4"><strong>William Chiwaya</strong><span style="color: black;"> LLB (Hons) Mw</span></a>
        <a href="#link4"><strong>Peter Dobiyala Minjale</strong><span style="color: black;"> LLB (Hons) Mw</span></a>
        <a href="#link4"><strong>Aaron Gausi</strong><span style="color: black;"> LLB (Hons) Mw</span></a>
        <a href="#link4"><strong>Charles Lupande</strong><span style="color: black;"> LLB (Hons) Mw</span></a>
        <a href="#link4"><strong>Tamara Tamaliza Manda</strong><span style="color: black;"> LLB (Hons) Mw</span></a>
        <a href="#link4"><strong>Mirriam Njobvu</strong><span style="color: black;"> LLB (Hons) Mw</span></a>
      </div>
    </div>
  </div>
  <div class="button">
    <?php include('button.php'); ?>
  </div>
</div>

</body>
</html>
