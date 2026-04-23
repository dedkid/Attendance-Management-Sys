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
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="css/addEmployeeIntern.css">
	<title>AMS | Employee and Intern Management</title>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="admin_dash.php" class="brand">
			<img src="images/CSK Logo.png" alt="" class="logo">
			<span class="text">Attendance Management System</span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="admin_dash.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="departments.php">
					<i class='bx bxs-business'></i>
					<span class="text">Departments</span>
				</a>
			</li>
			<li>
				<a href="attendances.php">
					<i class='bx bx-clipboard'></i>
					<span class="text">Attendance</span>
				</a>
			</li>
			<li>
				<a href="summary_view.php">
					<i class='bx bx-clipboard'></i>
					<span class="text">Summary View</span>
				</a>
			</li>
			<li class="active">
				<a href="users.php">
					<i class='bx bxs-calendar-check'></i>
					<span class="text">Add Employee/Intern</span>
				</a>
			</li>
			<li>
				<a href="accounts.php">
					<i class='bx bxs-user-account' ></i>
					<span class="text">Account Management</span>
				</a>
			</li>
			<li>
				<a href="leaves.php">
					<i class='bx bxs-calendar-exclamation'></i>
					<span class="text">Leaves</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>

	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="profile">
        	<i class='bx bxs-user-circle'></i>
			</a>
			<h2><?php echo $_SESSION['username']; ?></h2>
		</nav>

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Intern Management</h1>
				</div>
				</div>
				<form action="add_intern.php" method="POST">
					<div class="card-listAcc">
						<ul class="box-info">
							<h1>INTERN</h1></a>
						</ul>
					</div>
				<div class="input-box">
					<ul class="box-info">
						<div class="input-field">
							<input type="text" class="input" id="name" name="name" required autocomplete="off">
							<label for="name">Name</label>
						</div>
						<div class="input-field">
							<h4>Department</h4>
							<select id="dept" name="dept" required class="select-custom">
								<option value="">Choose department</option>
								<option value="Admin">Admin</option>
								<option value="HR">HR</option>
								<option value="Marketing">Marketing</option>
								<option value="Acccounting">Accounting</option>
								<option value="IT">IT</option>
							</select><br>
						</div>
						<div class="input-field">
							<input type="date" class="input" id="start_date" name="start_date" required>
							<label for="start_date">Start Date</label>
						</div>
						<div class="input-field">
							<input type="text" class="input" id="work_days" name="work_days" required>
							<label for="hr_req">Work Days (example: Monday to Friday)</label>
						</div>
						<div class="input-field">
							<input type="text" class="input" id="work_hrs" name="work_hrs" required>
							<label for="hr_req">Work Hours (example: 8am to 5pm)</label>
						</div>
						<div class="input-field">
							<input type="text" class="input" id="hr_req" name="hr_req" required>
							<label for="hr_req">Hours Required</label>
						</div>
						<div class="input-field">
							<input type="reset" class="submitClear" value="Clear">
							<input type="submit" class="submit" value="Add Intern">
						</div>
					</ul>
				</div>
			</form>

		</div>
			<div class="head-title">
				<div class="left">
					<h1>Employee Management</h1>
				</div>
			</div>
			<form action="add_emp.php" method="POST">
				<div class="card-listAcc">
					<ul class="box-info">
						<h1>EMPLOYEE</h1></a>
					</ul>
				</div>
				<div class="input-box">
					<ul class="box-info">
						<div class="input-field">
							<input type="text" class="input" id="name" name="name" required autocomplete="off">
							<label for="name">Name</label>
						</div>
						<div class="input-field">
							<h4>Department</h4>
							<select id="dept" name="dept" name="dept" class="select-custom">
								<option value="">Choose department</option>
								<option value="Admin">Admin</option>
								<option value="HR">HR</option>
								<option value="Marketing">Marketing</option>
								<option value="Acccounting">Accounting</option>
								<option value="IT">IT</option>
							</select><br>
						</div>
						<div class="input-field">
							<input type="text" class="input" id="position" name="position" required>
							<label for="position">Position</label>
						</div>
						<div class="input-field">
							<input type="text" class="input" id="work_days" name="work_days" required>
							<label for="hr_req">Work Days (example: Monday to Friday)</label>
						</div>
						<div class="input-field">
							<input type="text" class="input" id="work_hrs" name="work_hrs" required>
							<label for="hr_req">Work Hours (example: 8am to 5pm)</label>
						</div>
						<div class="input-field">
							<h4>Start Date</h4>
							<input type="date" class="input" id="start_date" name="start_date" required>
							
						</div>
						<div class="input-field">
							<input type="reset" class="submitClear" value="Clear">
							<input type="submit" class="submit" value="Add Employee">
						</div>
					</ul>
				</div>
			</form>
		</main>
    </div>
	</section>
	<!-- CONTENT -->
	<script src="js/Dashboard.js"></script>

</body>
</html>