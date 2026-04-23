<?php
	session_start();
	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

	include "connects.php";
	
    $page = 'add_userAcc';
    $tab = 'mngmt';
    include_once('sidebar.php');

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
    $formatted_date . "<br>Work Days: " . 
    $row['work_days'] . "<br>Work Hours: " . 
    $row['work_hrs'];

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

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="css/Dashboard.css">
	<link rel="stylesheet" href="css/admin_dash.css">
    <link rel="stylesheet" href="css/add_userAcc.css">
	<title>AMS | Dashboard</title>
</head>
<body>
	<!-- SIDEBAR -->
<?php include_once 'sidebar.php'; ?>


	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<h2><?php echo $_SESSION['username']; echo " | "; echo "AMS Admin"; echo "<br>";
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
            
			<div class="input-box">
				<ul class="box-info">
					<div class="input-field">
						<div class="date-time">
							<h1>Current Time and Date: <span id="live-time"></span></h1>
						</div>
					</div>
				</ul>
			</div>
			<ul class="box-info">
					<div class="input-field">
						<div class="center">
							<h1>Create Account</h1>
						</div>
					</div>
				</ul>
                <div class="container-useracc">
                <form action="add_mng.php" method="POST" class="input-box">
					<ul class="box-info">
                    <h1>Management</h1>
					<div class="input-field">
						<input type="text" class="input" name="name" required autocomplete="off">
						<label for="name">Name</label>
					</div>
					<div class="input-field">
						<input type="text" class="input" name="email" required autocomplete="off">
						<label for="email">Email (example@csk.com)</label>
					</div>
					<div class="input-field">
						<input type="password" class="input" name="password" required>
						<label for="password">Password</label>
					</div>
					<div class="input-field">
						<h4>Account Type</h4>
						<select name="role" required class="select-custom">
						<option value="">Choose type</option>
						<option value="admin">Admin</option>
						<option value="regular">Regular</option>
						</select><br>
						
					</div>
					<div class="input-field">
						<input type="text" name="position" required class="input">
                        <label for="position">Position</label>
					</div>
					<div class="input-field">
						<input type="reset" class="submitClear" value="Clear">
						<input type="submit" class="submit-btn" value="Add Management Account">  
					</div>
					</ul>
			</form>
            <form action="add_empInt.php" method="POST" class="input-box">
				<!---CREATE ACCOUNT CARD-->
					<ul class="box-info">
                    <h1>Employees/Interns</h1>
					<div class="input-field">
						<input type="text" class="input" name="name" required autocomplete="off">
						<label for="name">Full Name</label>
					</div>
					<div class="input-field">
						<input type="text" class="input" name="email" required autocomplete="off">
						<label for="email">Email (example@csk.com)</label>
					</div>
					<div class="input-field">
						<input type="password" class="input" name="password" required>
						<label for="password">Password</label>
					</div>
					<div class="input-field">
						<input type="text" name="position" required class="input">
                        <label for="position">Position</label>
					</div>
                    <div class="input-field">
						<input type="text" name="wrk_hrs" required class="input">
                        <label for="wrk_hrs">Work Hours</label>
					</div>
                    <div class="input-field">
						<input type="text" name="wrk_days" required class="input">
                        <label for="wrk_days">Work Days</label>
					</div>
                    <div class="input-field">
						<input type="text" class="input" name="school" placeholder="Only for Interns">
						<label for="school">School</label>
					</div>
                    <div class="input-field">
						<input type="number" class="input" name="hrs_req" placeholder="Only for Interns">
						<label for="hrs_req">Hours Required</label>
					</div>
					<div class="input-field">
						<h4>Account Type</h4>
						<select name="role" required class="select-custom">
						<option value="">Choose type</option>
						<option value="admin">Admin</option>
						<option value="regular">Regular</option>
						</select><br>
					</div>
                    <div class="input-field">
						<h4>Department</h4>
						<select name="department" required class="select-custom">
						<option value="">Select department</option>
						<option value="HR">HR</option>
						<option value="Marketing">Marketing</option>
                        <option value="Accounting">Accounting</option>
                        <option value="IT">IT</option>
                        <option value="Admin">Admin</option>
                        <option value="Management">Management</option>
						</select><br>
					</div>
                    <div class="input-field">
                        <h4>Date Started</h4>
						<input type="date" name="dateStarted" required class="input">
					</div>
					<div class="input-field">
						<input type="reset" class="submitClear" value="Clear">
						<input type="submit" class="submit-btn" value="Add Employee/Intern Account">  
					</div>
					</ul>
			</form>
</div>
			
		</main>
    </div>
	</section>
	<!-- CONTENT -->
	<script src="js/Dashboard.js"></script>
	<script src="js/navDropdown.js"></script>
	<script src="js/summaryView.js"></script>


	
</body>
</html>