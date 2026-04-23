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

    $query_it = "SELECT name, start_date, work_days, work_hrs FROM int_info WHERE department = 'IT'";
    $result_it = mysqli_query($conn, $query_it);

    $query_hr = "SELECT name, start_date, work_days, work_hrs FROM int_info WHERE department = 'HR'";
    $result_hr = mysqli_query($conn, $query_hr);

    $query_mkt = "SELECT name, start_date, work_days, work_hrs FROM int_info WHERE department = 'Marketing'";
    $result_mkt = mysqli_query($conn, $query_mkt);

    $query_act = "SELECT name, start_date, work_days, work_hrs FROM int_info WHERE department = 'Accounting'";
    $result_act = mysqli_query($conn, $query_act);

    $query_adm = "SELECT name, start_date, work_days, work_hrs FROM int_info WHERE department = 'Admin'";
    $result_adm = mysqli_query($conn, $query_adm);

    $query_emp = "SELECT name, department, position, start_date, work_days, work_hrs FROM emp_info";
    $result_emp = mysqli_query($conn, $query_emp);
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="css/Departments.css">
	<title>AMS | Departments</title>
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
			<li class="active">
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
					<h1>Departments</h1>
				</div>
			</div>
			<div class="card-listAcc">
				<ul class="box-info">
					<h1>IT INTERNS</h1>
				</ul>
			</div>
            <div id="it_dept"></div>
			<script type="text/javascript">
    				var itinfo_table = document.getElementById("it_dept");
   				    var it_table = "<table><tr><th>Name</th><th>Start Date</th><th>Work Days</th><th>Work Hours</th>";

    				<?php while($row_in = mysqli_fetch_assoc($result_it)) { ?>
        			it_table += "<tr><td><?php echo $row_in['name']; ?></td><td><?php echo date('D, M d, Y', strtotime($row_in['start_date'])); ?></td><td><?php echo $row_in['work_days']; ?></td><td><?php echo $row_in['work_hrs']; ?></td>";
    				<?php } ?>

    				it_table += "</table>";
    				itinfo_table.innerHTML = it_table;
			</script>

			<div class="card-listAcc">
				<ul class="box-info">
					<h1>MARKETING INTERNS</h1>
				</ul>
			</div>
            <div id="mkt_dept"></div>
			<script type="text/javascript">
    				var mktinfo_table = document.getElementById("mkt_dept");
   				    var mkt_table = "<table><tr><th>Name</th><th>Start Date</th><th>Work Days</th><th>Work Hours</th>";

    				<?php while($row_in = mysqli_fetch_assoc($result_mkt)) { ?>
        			mkt_table += "<tr><td><?php echo $row_in['name']; ?></td><td><?php echo date('D, M d, Y', strtotime($row_in['start_date'])); ?></td><td><?php echo $row_in['work_days']; ?></td><td><?php echo $row_in['work_hrs']; ?></td>";
    				<?php } ?>

    				mkt_table += "</table>";
    				mktinfo_table.innerHTML = mkt_table;
			</script>

			<div class="card-listAcc">
				<ul class="box-info">
					<h1>ACCOUNTING INTERNS</h1>
				</ul>
			</div>
            <div id="act_dept"></div>
			<script type="text/javascript">
    				var actinfo_table = document.getElementById("act_dept");
   				    var act_table = "<table><tr><th>Name</th><th>Start Date</th><th>Work Days</th><th>Work Hours</th>";

    				<?php while($row_in = mysqli_fetch_assoc($result_act)) { ?>
        			act_table += "<tr><td><?php echo $row_in['name']; ?></td><td><?php echo date('D, M d, Y', strtotime($row_in['start_date'])); ?></td><td><?php echo $row_in['work_days']; ?></td><td><?php echo $row_in['work_hrs']; ?></td>";
    				<?php } ?>

    				act_table += "</table>";
    				actinfo_table.innerHTML = act_table;
			</script>

			<div class="card-listAcc">
				<ul class="box-info">
					<h1>HUMAN RESOURCES INTERNS</h1>
				</ul>
			</div>
            <div id="hr_dept"></div>
			<script type="text/javascript">
    				var hrinfo_table = document.getElementById("hr_dept");
   				    var hr_table = "<table><tr><th>Name</th><th>Start Date</th><th>Work Days</th><th>Work Hours</th>";

    				<?php while($row_in = mysqli_fetch_assoc($result_hr)) { ?>
        			hr_table += "<tr><td><?php echo $row_in['name']; ?></td><td><?php echo date('D, M d, Y', strtotime($row_in['start_date'])); ?></td><td><?php echo $row_in['work_days']; ?></td><td><?php echo $row_in['work_hrs']; ?></td>";
    				<?php } ?>

    				hr_table += "</table>";
    				hrinfo_table.innerHTML = hr_table;
			</script>

			<div class="card-listAcc">
				<ul class="box-info">
					<h1>ADMIN INTERNS</h1>
				</ul>
			</div>
            <div id="adm_dept"></div><br>
			<script type="text/javascript">
    				var adminfo_table = document.getElementById("adm_dept");
   				    var adm_table = "<table><tr><th>Name</th><th>Start Date</th><th>Work Days</th><th>Work Hours</th>";

    				<?php while($row_in = mysqli_fetch_assoc($result_adm)) { ?>
        			adm_table += "<tr><td><?php echo $row_in['name']; ?></td><td><?php echo date('D, M d, Y', strtotime($row_in['start_date'])); ?></td><td><?php echo $row_in['work_days']; ?></td><td><?php echo $row_in['work_hrs']; ?></td>";
    				<?php } ?>

    			    adm_table += "</table>";
    				adminfo_table.innerHTML = adm_table;
			</script>

			<div class="card-listAcc">
				<ul class="box-info">
					<h1>EMPLOYEES</h1>
				</ul>
			</div>
            <div id="emp"></div>
			<script type="text/javascript">
    				var empinfo_table = document.getElementById("emp");
   				    var emp_table = "<table><tr><th>Name</th><th>Department</th><th>Position</th><th>Start Date</th><th>Work Days</th><th>Work Hours</th>";

    				<?php while($row_in = mysqli_fetch_assoc($result_emp)) { ?>
        			emp_table += "<tr><td><?php echo $row_in['name']; ?></td><td><?php echo $row_in['department']; ?></td><td><?php echo $row_in['position']; ?></td><td><?php echo date('D, M d, Y', strtotime($row_in['start_date'])); ?></td><td><?php echo $row_in['work_days']; ?></td><td><?php echo $row_in['work_hrs']; ?></td>";
    				<?php } ?>

    			    emp_table += "</table>";
    				empinfo_table.innerHTML = emp_table;
			</script>
		</main>
    </div>
	</section>
	<!-- CONTENT -->
	<script src="js/Dashboard.js"></script>

</body>
</html>