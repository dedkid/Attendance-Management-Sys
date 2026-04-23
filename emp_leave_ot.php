<?php
    session_start();

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    require_once('connects.php');

    if(isset($_SESSION['username'])) {
    //do nothing
    } else {
       header('Location: index.php');
       exit;
    }

	$name = $_SESSION['username'];
    $position = $_SESSION['position'];
?>

<style>
	a.download-link {
		text-decoration: none;
		color: yellow;
	}

	a.download-link:hover {
		text-decoration: underline;
	}

	a.download-link:visited {
		color: purple;
	}
</style>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="css/regularLeaveOT.css">
	<title>AMS | Dashboard</title>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="reg_dash.php" class="brand">
			<img src="images/CSK Logo.png" alt="" class="logo">
			<span class="text">Attendance Management System</span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="reg_dash.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="reg_attendance.php">
					<i class='bx bxs-calendar-check'></i>
					<span class="text">Attendance</span>
				</a>
			</li>
			<li>
				<a href="reg_leave_ot.php" class="active">
					<i class='bx bxs-calendar-x'></i>
					<span class="text">Request Leave/Overtime</span>
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
					<h1>Request Leave</h1>
				</div>
			</div>
			<div class="card-listAcc">
				<ul class="box-info">
					<text>STEPS FOR REQUESTING LEAVE</text>
				</ul>
			</div>
			<div class="input-box">
				<ul class="box-info">
				<h4>
					1.  <a href="https://drive.google.com/drive/u/1/folders/1bDpwecODPGPm0X0Thdj-2cTsgUjHd0JM" class="download-link">Download the leave form here</a>
				</h4>
				<h4>
					2. Send email to the officer with your form
				</h4>
				<div class="input-field">
					<button type="submit" name="pl" class="submit">
					<a href="mailto:?subject=Sick%20Leave%20-%20<?php echo rawurlencode($name); ?>">Sick Leave</a>
					</button>
				</div>
				<div class="input-field">
					<button type="submit" name="sil" class="submit">
					<a href="mailto:?subject=Birthday%20Leave%20-%20<?php echo rawurlencode($name); ?>">Birthday Leave</a>
					</button>
				</div>  
				<div class="input-field">                  
					<button type="submit" name="sil" class="submit">
					<a href="mailto:?subject=Vacation%20Leave%20-%20<?php echo rawurlencode($name); ?>">Vacation Leave</a>
					</button>
				</div>
				<div class="input-field">
					<button type="submit" name="el" class="submit">
					<a href="mailto:?subject=Emergency%20Leave%20-%20<?php echo rawurlencode($name); ?>">Emergency Leave</a>
					</button>
				</div>
				<h4>
					3. Wait for officer remarks.
				</h4>
				</ul>
			</div>
			<br>
			<div class="head-title">
				<div class="left">
					<h1>Request Overtime</h1>
				</div>
			</div>	
			<div class="card-listAcc">
				<ul class="box-info">
				<text>LIST OF EMAILS</text>
				</ul>
			</div>
			<table>
			<tr>
				<th>Email</th>
				<th>Name</th>
				<th>Position</th>
			</tr>
			<tr>
				<td>ceo.chrisimm@yahoo.com</td>
				<td>Ms. Cherizza Penafiel</td>
				<td>CEO</td>
			</tr>
			<tr>
				<td>ao.chrisimm@gmail.com</td>
				<td>Ms. Jazz Jan Gregorio</td>
				<td>Admin</td>
			</tr>
			<tr>
				<td>qcm.chrisimm@gmail.com</td>
				<td>Mr. Viejay Abad</td>
				<td>IT/Marketing Manager</td>
			</tr>
			<tr>
				<td>it.chrisimm.c2j@gmail.com</td>
				<td>Mr. John Michael De Jose</td>
				<td>IT/Marketing Supervisor</td>
			</tr>
			<tr>
				<td>ea.chrisimm@gmail.com</td>
				<td>Mr. John Lenard Gregorio</td>
				<td>EA</td>
			</tr>
			<tr>
				<td>hrd.chrisimm@gmail.com</td>
				<td>Mr. Julich Enduma</td>
				<td>HRD Supervisor</td>
			</tr>
			<tr>
				<td>justinepieto@gmail.com</td>
				<td>Mr. Justine Pieto</td>
				<td>Accounting Supervisor</td>
			</tr>
			</table>
			<div class="input-field">
				<button type="submit" name="overtime" class="submit">
					<a href="mailto:?subject=Request%20for%20Overtime%20-%20<?php echo rawurlencode($name); ?>">Email Officer for OT</a>
				</button>
			</div>
			
		</main>
    </div>
	</section>
	<!-- CONTENT -->
	<script src="js/Dashboard.js"></script>

</body>
</html>