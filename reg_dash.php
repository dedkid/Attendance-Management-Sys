<?php
	session_start();
	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

	include "connects.php";
	include "access_control.php";

	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
		exit();
	}

	$name = $_SESSION['username'];
    $position = $_SESSION['position'];

    if($position == "employee") {
        $query = "SELECT name, department, position, start_date, work_days, work_hrs FROM emp_info WHERE name='$name'";
    } else {
        $query = "SELECT name, department, position, start_date, hr_req, hr_ren, hr_left FROM int_info WHERE name='$name'";
    }
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $date = 
    $row['start_date'];
    $formatted_date = date('D, M d, Y', strtotime($date));
    $result_text = "<h1>Name: " . 
    $row['name'] . "<br>Department: " . 
    $row['department'] . "<br>Position: " . 
    $row['position'] . "<br>Start Date: " . 
    $formatted_date . "<br>Work Days: ";

    if($position == "intern") {
        $result_text .= "<br>Hours Required: " . 
        $row['hr_req'] . "<br>Hours Rendered: " . 
        $row['hr_ren'] . "<br>Hours Left: " . 
        $row['hr_left'];
    }
	
    mysqli_close($conn);
	
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--IconsScout-->
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
        <!-- Boxicons -->
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="css/Dashboard.css">
	<link rel="stylesheet" href="css/admin_dash.css">
	
	<title>AMS | Dashboard</title>
</head>
<body>
	 <!-- SIDEBAR -->
	 <section id="sidebar">
    <a href="admin_dash.php" class="brand">
        <img src="images/CSK Logo.png" alt="" class="logo">
        <span class="text">Attendance Management System</span>
    </a>
    <ul class="side-menu top">
        <li class="active">
            <a href="reg_dash.php">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li>
            <a id="switch" style="cursor: default;">

                <i class='bx bx-clipboard'></i>
                <span class="text">Attendance</span>
            </a>
        </li>
        <div class="dropdown" style="display: none;">
            <li>
                <a href="reg_inout.php">

                    <i class='bx bx-table'></i>

                    <span class="text">Time In/Out</span>
                </a>
            </li>
            <li>
                <a href="int_emp_dtr_view.php">

                    <i class='bx bx-windows'></i>
                    <span class="text">Tasks/DTRs</span>
                </a>
            </li>
        </div>


			
			

			<li>
				<a href="under_dev.html">
					<i class='bx bxs-calendar-exclamation' ></i>

					<span class="text">My Notices</span>
				</a>
			</li>

			<li>

				<a href="under_dev.html">
					<i class='bx bx bx-user-circle'></i>

					<span class="text">My Profile</span>
				</a>
			</li>
            <li>

				<a href="csk.php">
					<i class='bx bx-globe'></i>

					<span class="text">CSK</span>

				</a>
			</li>

		</ul>
		
	</section>




	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<h2><?php echo $_SESSION['username']; echo " | "; echo "AMS Regular"; echo "<br>";
			echo $row['position']; echo " | "; echo $row['department'];
			?></h2>

			<li>
				<a href="logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</nav>

		<!-- MAIN -->
		<main>
			<div class="head-title">
				
			<table class="table-holder">
				<thead>
					<tr>
					<th><h1>Announcement</th>
				<!-- ROW HEADER HERE-->
					</tr>
				</thead>
				<tbody class="tbody-holder">
					<tr>
					<td>
						Reminder:
						Don't forget to answer this and that. Never forget to do this. Answer this form. How much limit will this space take? Testing this and that. Testing again. Testing. I think this should be good. Don't forget this.
					</td>	
					<!-- BODY/COLUMNS HERE -->
				</tr>
				</tbody>
			</table>
			</div>
			<div class="input-box">
				<ul class="box-info">
					<div class="input-field">
						<div class="date-time">
							<h1>Current Time and Date: <span id="live-time"></span></h1>
						</div>
					</div>
				</ul>
			</div>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
				</div>
			</div>

<div class="grid-container">
<div class="grid-item">
  <a href="#">
    <i class='uil uil-clock-eight logo-icon'></i>
    <figcaption id="ucaption" class="caption">Time In</figcaption>
  </a>
</div>
<div class="grid-item">
  <a href="#">
  <i class='uil uil-clock-five logo-icon'></i>
    <figcaption id="ucaption" class="caption">Time Out</figcaption>
  </a>
</div>
<div class="grid-item">
  <a href="int_emp_dtr_view.php">
    <i class='bx bx-windows logo-icon'></i>
    <figcaption class="caption">Activities/DTRs</figcaption>
  </a>
</div>
<div class="grid-item">
  <a href="under_dev.html">
    <i class='bx bx-bell logo-icon'></i>
    <figcaption class="caption">My Notices</figcaption>
  </a>
</div>
<div class="grid-item">
  <a href="under_dev.html">
    <i class='bx bx-user-circle logo-icon'></i>
    <figcaption class="caption">My Profile</figcaption>
  </a>
</div>
<div class="grid-item">
  <a href="under_dev.html">
    <i class='bx bx-body logo-icon'></i>
    <figcaption class="caption">CSK Org. Chart</figcaption>
  </a>
</div>
<div class="grid-item">
  <a href="https://www.chrisimmsk-c2j.com">
    <img src="/images/CSK Logo.png" class='logo-icon'>
    <figcaption class="caption">CSK Web Page</figcaption>
  </a>
</div>

    		</div>

			
		</main>
    </div>
	</section>
	<!-- CONTENT -->
	<script src="js/Dashboard.js"></script>
	<script src="js/navDropdown.js"></script>

	<script>
  function formatDate(dateNow) {
    const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
    return dateNow.toLocaleDateString(undefined, options);
  }

  function updateTime() {
    var dateNow = new Date();
    var hours = dateNow.getHours();
    var minutes = dateNow.getMinutes();
    var seconds = dateNow.getSeconds();
    var ampm = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12;
    hours = hours ? hours : 12;

    hours = hours.toString().padStart(2, '0');
    minutes = minutes.toString().padStart(2, '0');
    seconds = seconds.toString().padStart(2, '0');

    var currentDate = formatDate(dateNow);
    document.getElementById("live-time").textContent = hours + ":" + minutes + ":" + seconds + ' ' + ampm + " | " + currentDate;
  }

  updateTime(); // Call the function initially to display the time immediately
  setInterval(updateTime, 1000); // Call the function every second to update the time

  
</script>

	
</body>
</html>