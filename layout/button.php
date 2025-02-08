<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.button {
  border-radius: 7px;
  background-color:rgba(194, 144, 70, 0.78); /* Button background color */
  border: none;
  color: #FFFFFF; /* Button text color */
  text-align: center;
  font-size: 18px; /* Button font size */
  padding: 7px 13px; /* Button padding */
  width: auto; /* Button width */
  transition: all 0.5s;
  cursor: pointer;
  text-decoration: none; /* Remove underline from link */
  display: inline-block;
  margin: 2px;
}

.button span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  transition: 0.5s;
}

.button span:after {
  content: '\00bb';
  position: absolute;
  opacity: 0;
  top: 0;
  right: -20px;
  transition: 0.5s;
}

.button:hover span {
  padding-right: 20px;
}

.button:hover span:after {
  opacity: 1;
  right: 0;
}

.button:hover {
  background-color:#333;
  /* background-color:rgba(59, 187, 238, 0.88); Darker background on hover */
}
</style>
</head>
<body>

<a href="../jtk/registration/register.php" class="button"><span>Get Started <i class="fa fa-fw fa-arrow-right"></i></span></a>

</body>
</html>
