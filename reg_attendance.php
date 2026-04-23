<?php
    session_start();

	header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

	include "connects.php";

	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
		exit();
	}

	$name = $_SESSION['username'];
	$position = $_SESSION['position'];
	$title_mod = "";

	$buti = "enabled";
	$buto = "enabled";

	$check_status = "SELECT status FROM users WHERE name='$name'";
    $result_status = mysqli_query($conn, $check_status);

    if (mysqli_num_rows(($result_status)) > 0) {
        $row_status = mysqli_fetch_assoc($result_status);
        $user_status = ($row_status['status']);
		if ($user_status == "in") {
			$buti = "disabled";
		}
		elseif ($user_status == "out") {
			$buto = "disabled";
		}
	} else {
		echo "An error occurred.";
	}

	


	if($position === "employee"){
		$title_mod = "OFFICER";
	}else{
		$title_mod = "SUPERVISOR";
	}

    $query_in = "SELECT datetime, location, photo_loc FROM time_in WHERE name='$name' ORDER BY datetime DESC";
    $result_in = mysqli_query($conn, $query_in);

    $query_out = "SELECT datetime, overtime, hours, tasks FROM time_out WHERE name='$name' ORDER BY datetime DESC";
    $result_out = mysqli_query($conn, $query_out);

	$query_leaves = "SELECT leave_type, date_req, date_app, status, remarks FROM leaves WHERE name='$name' ORDER BY date_req DESC";
    $result_leaves = mysqli_query($conn, $query_leaves);

	$query_notice = "SELECT type, date, remarks FROM notices WHERE name='$name'";
    $result_notice = mysqli_query($conn, $query_notice);


?>

<script>
	function formatDate(date) {
      const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
      return date.toLocaleDateString(undefined, options);
    }

    function updateTime() {
      var date = new Date();
      var hours = date.getHours();
      var minutes = date.getMinutes();
      var seconds = date.getSeconds();
      var ampm = hours >= 12 ? 'PM' : 'AM';

      hours = hours % 12;
      hours = hours ? hours : 12;

      hours = hours.toString().padStart(2, '0');
      minutes = minutes.toString().padStart(2, '0');
      seconds = seconds.toString().padStart(2, '0');

      var currentDate = formatDate(date);
      document.getElementById("live-time").textContent = currentDate + " " + hours + ":" + minutes + ":" + seconds + ' ' + ampm;
    }

    setInterval(updateTime, 1000);
</script>

<style>
	.correct-time {
		color: black;
	}

	.late-time {
		color: red;
	}

	.policy {
	display: none;
	}

	.info:hover + .policy {
	display: block;
	color: black;
	}
</style>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<!-- My CSS -->
	<link rel="stylesheet" href="css/regularAttendance.css">
	
	<title>AMS | Attendance</title>
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
			<li class="active">
				<a href="reg_attendance.php">
					<i class='bx bxs-calendar-check'></i>
					<span class="text">Attendance</span>
				</a>
			</li>
			<li>
				<a href="redirect_emp_int.php">
					<i class='bx bxs-calendar-x'></i>
					<span class="text">Request Leave/Overtime</span>
				</a>
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
					<h1>Attendances</h1>
				</div>
			</div>
			<div class="card-listAcc">
				<ul class="box-info">
					<text>CURRENT DATE AND TIME</text>
				</ul>
			</div>
			<div class="input-box">
				<ul class="box-info">
					<div class="input-field">
						<div class="date-time">
							<h1><span id="live-time"></span></h1>
						</div>
						<div class="input-field">	
							<form method="POST" action="time_in.php" enctype="multipart/form-data">
							<div class="info"><h2>Time-in:</h2></div>
							<div class="info"><h4>Picture of you and your workspace</h4></div>
							<div class="policy" role="dialog">
								<h3 id="modal-heading">Data Privacy and Consent</h3>
								<p>By uploading your photo, you consent to the collection and processing of your personal data which will be used only for attendance monitoring.</p>
							</div>
							<input type="file" id="photo" name="photo" required><br>
							<h4>Location</h4>
							<select name="location" class="select-custom">
								<option value="WFH">Work From Home</option>
								<option value="Imus">Imus</option>
								<option value="Evia">Evia</option>
							</select><br>
							<button type="submit" name="timein" class="submit"<?php echo $buti; ?>>Time In</button><br><br><br>
							</form>
						</div>
						<div class="info"><h2>Time-out:</h2></div>
						<div class="input-field">
							<h4>Overtime</h4>
							<form method="POST" action="time_out.php">
							<select id="overtime" name="overtime" class="select-custom">
								<option value="0">No overtime</option>
								<option value="1">1 hour</option>
								<option value="2">2 hours</option>
								<option value="3">3 hours</option>
								<option value="4">4 hours</option>
							</select><br>
							<div id="taskstext"></div>
							<div class="input-field">
                            <textarea id="taskstext" name="tasks" class="input" minlength="50"required></textarea>
                            <label for="tasks">Tasks</label>
							</div>
							<button type="submit" name="timeout" class="submit"<?php echo $buto; ?>>Time Out</button>
							</form>
						</div>
					</div>
				</ul>
			</div><br>

			<!--- time in start --->
			<?php
			$in_rowsPerPage = 10;
			$in_pageNum = isset($_GET['in_page']) ? (int)$_GET['in_page'] : 1;
			$in_limit = 'LIMIT ' . (($in_pageNum - 1) * $in_rowsPerPage) . ', ' . $in_rowsPerPage;

			$in_sql = "SELECT datetime, location, photo_loc FROM time_in WHERE name='$name' ORDER BY datetime DESC $in_limit";
			$in_result = mysqli_query($conn, $in_sql);

			$in_sqlCount = "SELECT COUNT(*) as count FROM time_in WHERE name='$name'";
			$in_resultCount = mysqli_query($conn, $in_sqlCount);
			$in_rowCount = mysqli_fetch_assoc($in_resultCount)['count'];
			$in_totalPages = ceil($in_rowCount / $in_rowsPerPage);
			if ($in_totalPages > 1 && $in_rowCount < ($in_pageNum - 1) * $in_rowsPerPage) {
				$in_totalPages = ceil($in_rowCount / $in_rowsPerPage);
				if ($in_totalPages == 0) {
					$in_totalPages = 1;
				}
			}
			?>

			<div class="card-listAcc">
				<ul class="box-info">
					<h1>TIME IN</h1>
				</ul>
			</div>
			<table>
				<thead>
					<tr>
						<th>Date and Time</th>
						<th>Location</th>
						<th>Photo</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($row = mysqli_fetch_assoc($in_result)) { ?>
						<?php
						$time = strtotime($row['datetime']);
						$timeFormatted = date('H:i', $time);
						$isHighlight = (
							($timeFormatted <= '07:45' || $timeFormatted >= '08:05') &&
							($timeFormatted <= '16:45' || $timeFormatted >= '17:05')
						  );
						$timeClass = $isHighlight ? 'late-time' : 'correct-time';
						?>
					<tr>
						<td class="<?php echo $timeClass; ?>"><?php echo date('D, M d, Y h:i A', strtotime($row['datetime'])); ?></td>
						<td><?php echo $row['location']; ?></td>
						<td><a href="<?php echo $row['photo_loc']; ?>">View Photo</a></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

			<?php if ($in_totalPages > 1) { ?>
			<div class="page-Num">
				<?php if ($in_pageNum > 1) { ?>
				<a href="?in_page=<?php echo ($in_pageNum - 1); ?>">Prev</a>
				<?php } ?>
				<?php for ($i = 1; $i <= $in_totalPages; $i++) { ?>
				<?php if ($i == $in_pageNum) { ?>
					<strong><?php echo $i; ?></strong>
				<?php } else { ?>
					<a href="?in_page=<?php echo $i; ?>"><?php echo $i; ?></a>
				<?php } ?>
				<?php } ?>
				<?php if ($in_pageNum < $in_totalPages) { ?>
				<a href="?in_page=<?php echo ($in_pageNum + 1); ?>">Next</a>
				<?php } ?>
			</div>
			<?php } ?>
			<!--- time in end --->

			<!--- time out start --->
			<?php
			$out_rowsPerPage = 10;
			$out_pageNum = isset($_GET['out_page']) ? (int)$_GET['out_page'] : 1;
			$out_limit = 'LIMIT ' . (($out_pageNum - 1) * $out_rowsPerPage) . ', ' . $out_rowsPerPage;

			$out_sql = "SELECT datetime, overtime, hours, tasks, approval FROM time_out WHERE name='$name' AND approval<>'No Time-out' ORDER BY datetime DESC $out_limit";
			$out_result = mysqli_query($conn, $out_sql);

			$out_sqlCount = "SELECT COUNT(*) as count FROM time_out WHERE name='$name'";
			$out_resultCount = mysqli_query($conn, $out_sqlCount);
			$out_rowCount = mysqli_fetch_assoc($out_resultCount)['count'];
			$out_totalPages = ceil($out_rowCount / $out_rowsPerPage);

			if ($out_totalPages > 1 && $out_rowCount < ($out_pageNum - 1) * $out_rowsPerPage) {
				$out_totalPages = ceil($out_rowCount / $out_rowsPerPage);
				if ($out_totalPages == 0) {
					$out_totalPages = 1;
				}
			}
			?>

			<div class="card-listAcc">
				<ul class="box-info">
					<h1>TIME OUT</h1>
				</ul>
			</div>
			<table style="table-layout: fixed; width: 100%">
				<thead>
					<tr>
						<th>Status</th>
						<th>Date and Time</th>
						<th>Overtime</th>
						<th>Hours</th>
						<th>Tasks</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($row = mysqli_fetch_assoc($out_result)) { ?>
						<?php
						$time = strtotime($row['datetime']);
						$timeFormatted = date('H:i', $time);
						$isHighlight = (
							($timeFormatted <= '07:45' || $timeFormatted >= '08:05') &&
							($timeFormatted <= '16:45' || $timeFormatted >= '17:05')
						  );
						$timeClass = $isHighlight ? 'late-time' : 'correct-time';
						?>
					<tr>
						<td><?php echo ($row['approval']); ?></td>
						<td class="<?php echo $timeClass; ?>"><?php echo date('D, M d, Y h:i A', strtotime($row['datetime'])); ?></td>
						<td><?php echo $row['overtime']; ?></td>
						<td><?php echo $row['hours']; ?></td>
						<td style="word-wrap: break-word"><?php echo nl2br ($row['tasks']); ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

			<?php if ($out_totalPages > 1) { ?>
			<div class="page-Num">
				<?php if ($out_pageNum > 1) { ?>
				<a href="?out_page=<?php echo ($out_pageNum - 1); ?>">Prev</a>
				<?php } ?>
				<?php for ($i = 1; $i <= $out_totalPages; $i++) { ?>
				<?php if ($i == $out_pageNum) { ?>
					<strong><?php echo $i; ?></strong>
				<?php } else { ?>
					<a href="?out_page=<?php echo $i; ?>"><?php echo $i; ?></a>
				<?php } ?>
				<?php } ?>
				<?php if ($out_pageNum < $out_totalPages) { ?>
				<a href="?out_page=<?php echo ($out_pageNum + 1); ?>">Next</a>
				<?php } ?>
			</div>
			<?php } ?>
			<!--- time out end --->

			<!--- leaves start --->
			<div class="card-listAcc">
				<ul class="box-info">
					<h1>LEAVES</h1>
				</ul>
			</div>
			<table>
				<thead>
					<tr>
						<th>Leave Type</th>
						<th>Date Requested</th>
						<th>Date Approved</th>
						<th>Status</th>
						<th>Remarks</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($row = mysqli_fetch_assoc($result_leaves)) { ?>
					<tr>
						<td><?php echo $row['leave_type'] ?></td>
						<td><?php echo date('D, M d, Y h:i A', strtotime($row['date_req'])); ?></td>
						<td><?php echo date('D, M d, Y h:i A', strtotime($row['date_app'])); ?></td>
						<td><?php echo $row['status']; ?></td>
						<td><?php echo $row['remarks']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<!--- leaves end --->

			<!--- notices start --->
			<div class="card-listAcc">
				<ul class="box-info">
					<h1>NOTICE FROM <?php echo $title_mod ?></h1>
				</ul>
			</div>
			<table>
				<thead>
					<tr>
						<th>Type</th>
						<th>Date</th>
						<th>Remarks</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($row = mysqli_fetch_assoc($result_notice)) { ?>
					<tr>
						<td><?php echo $row['type']; ?></td>
						<td><?php echo date('D, M d, Y', strtotime($row['date'])); ?></td>
						<td><?php echo $row['remarks']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<!--- notices end --->
		</main>
	</section>
	<!-- CONTENT -->
	<script src="js/Dashboard.js"></script>

</body>
</html>