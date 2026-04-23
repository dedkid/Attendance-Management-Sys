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

	$query_email = "SELECT email FROM users";
    $result_email = mysqli_query($conn, $query_email);

    $query_in = "SELECT email, password, name FROM users";
    $result_in = mysqli_query($conn, $query_in);

	$query_int = "SELECT name FROM int_info";
	$result_int = mysqli_query($conn, $query_int);
	
	$query_emp = "SELECT name FROM emp_info";
	$result_emp = mysqli_query($conn, $query_emp);
	$combinedNames = array();
	
	while ($row1 = mysqli_fetch_assoc($result_int)) {
	  $combinedNames[] = $row1['name'];
	}
	
	while ($row2 = mysqli_fetch_assoc($result_emp)) {
	  $combinedNames[] = $row2['name'];
	}
?>

<script>
	function showConfirmation() {
		return confirm("This will delete ALL user's data. Do you want to proceed?");
	}
</script>

<!DOCTYPE html>
<html lang="en">
<head>
  	<meta charset="UTF-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<title>AMS | Account Management</title>
  	<!-- Boxicons -->
 	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
  	<!-- My CSS -->
  	<link rel="stylesheet" href="css/createAccounts.css">
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
			<li>
				<a href="users.php">
					<i class='bx bxs-calendar-check'></i>
					<span class="text">Add Employee/Intern</span>
				</a>
			</li>
			<li class="active">
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
			<a href="#" class="nav-link"></a>
		
			<a href="#" class="profile">
            <i class='bx bxs-user-circle'></i>
			</a>
			<h2><?php echo $_SESSION['username']; ?></h2>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Account Management</h1>
				</div>
			</div>

			<form action="add_acct.php" method="POST">
				<!---CREATE ACCOUNT CARD-->
				<div class="card-listAcc">
					<ul class="box-info">
						<h1>CREATE ACCOUNT</h1></a>
					</ul>
				</div>
				<div class="input-box">
					<ul class="box-info">
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
						<h4>Type of User</h4>
						<select name="role" required class="select-custom">
						<option value="">Choose role</option>
						<option value="admin">Admin</option>
						<option value="regular">Regular</option>
						</select><br>
						
					</div>
					<div class="input-field">
						<h4>Position</h4>
						<select name="position" required class="select-custom">
						<option value="">Choose position</option>
						<option value="employee">Employee</option>
						<option value="intern">Intern</option>
						</select><br>
					</div>
					<div class="input-field">
						<input type="reset" class="submitClear" value="Clear">
						<input type="submit" class="submit" value="Add Account">  
					</div>
					</ul>
				</div>
			</form>

			<form action="forgot_pw.php" method="POST">
				<!---CREATE ACCOUNT CARD-->
				<div class="card-listAcc">
					<ul class="box-info">
						<h1>ACCOUNT RECOVERY</h1></a>
					</ul>
				</div>
				<div class="input-box">
					<ul class="box-info">
					<div class="input-field">
						<h4>Email</h4>
						<select id="email" name="email" required class="select-custom">
							<option value="">Choose email</option>
							<?php while ($row = mysqli_fetch_assoc($result_email)) { ?>
        						<option value="<?php echo $row['email']; ?>"><?php echo $row['email']; ?></option>
    						<?php } ?>
						</select>
					</div><br>
					<div class="input-field">
						<input type="password" class="input" name="password" required>
						<label for="password">New Password</label>
					</div>
					<div class="input-field">
						<input type="reset" class="submitClear" value="Clear">
						<input type="submit" class="submit" value="Change Password">  
					</div>
					</ul>
				</div>
			</form>

			<form action="delete_acct.php" method="POST" onsubmit="return showConfirmation()">
				<!---CREATE ACCOUNT CARD-->
				<div class="card-listAcc">
					<ul class="box-info">
						<h1>DELETE ACCOUNT</h1></a>
					</ul>
				</div>
				<div class="input-box">
					<ul class="box-info">
					<div class="input-field">
						<h4>Name</h4>
						<select id="name" name="name" required class="select-custom">
							<option value="">Choose a name</option>
							<?php
							// Generate the options for the drop-down menu
							foreach ($combinedNames as $name) {
								echo "<option value='$name'>$name</option>";
							}
							?>
						</select>
					</div>
					<div class="input-field">
						<input type="submit" class="submit" value="Delete Account">  
					</div>
					</ul>
				</div>
			</form>

				<!--LIST OF ACCOUNTS-->
				<div class="card-listAcc">
					<ul class="box-info">
						<h1>LIST OF ACCOUNTS</h1>
					</ul>
				</div>
				<div id="accounts"></div>

			<script type="text/javascript">
				var accounts_table = document.getElementById("accounts");
				var acc_table = "<table><tr><th>NAME</th><th>EMAIL</th><th>PASSWORD</th></tr>";
				<?php while($row_in = mysqli_fetch_assoc($result_in)) { ?>
				acc_table += "<tr><td><?php echo $row_in['name']; ?></td><td><?php echo $row_in['email']; ?></td><td><?php echo $row_in['password']; ?></td></tr>";
				<?php } ?>
				acc_table += "</table>";
				accounts_table.innerHTML = acc_table;
			</script>
		</main>
    </div>
	</section>
    <script src="js/Dashboard.js"></script>
</body>
</html>