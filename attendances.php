<?php
	session_start();
	header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

	include "connects.php";
	include "access_control.php";

	$page = 'attendances';
	$tab = 'attendance';
	include_once('sidebar.php');

	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
		exit();
	}

	$name = $_SESSION['username'];
    $position = $_SESSION['position'];

	$query_name = "SELECT name FROM int_info";
    $result_name = mysqli_query($conn, $query_name);

    $query_in = "SELECT name, department, start_date, hr_req, hr_ren, hr_left FROM int_info";
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

	
	$query_notice = "SELECT name, type, date, remarks FROM notices";
    $result_notice = mysqli_query($conn, $query_notice);

	
	$query_approval = "SELECT * FROM time_out WHERE approval = 'Reviewing'";
	$result_approval = mysqli_query($conn, $query_approval);

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

	
?>

<script>
	function fetchUserData(selectedName) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {
			var userData = JSON.parse(xhr.responseText);
			document.getElementById('hr_req').value = userData.hr_req;
			document.getElementById('hr_ren').value = userData.hr_ren;
			document.getElementById('hr_left').value = userData.hr_left;
		}
		};
		xhr.open('GET', 'fetch_int_hours.php?name=' + selectedName, true);
		xhr.send();
  	}

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
</style>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="css/Attendances.css">
	<title>AMS | Attendance</title>
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
			<div class="head-title">
				<div class="left">
					<h1>Attendances</h1>
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
					</div>
				</ul>
			</div><br>
			
			<!--- Time in start --->
			<?php
			$rowsPerPage = 10;
			$pageNum = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
			$limit = 'LIMIT ' . (($pageNum - 1) * $rowsPerPage) . ', ' . $rowsPerPage;
			
			$filterType = isset($_GET['filter_type']) ? $_GET['filter_type'] : '';
			
			$sql = "SELECT name, datetime, location, photo_loc FROM time_in";
			
			// Apply the appropriate filter based on the selected option
			if ($dateFilter != '') {
			  if ($filterType === 'year') {
				// Filter by year
				$sql .= " WHERE YEAR(datetime) = YEAR('$dateFilter')";
			  } elseif ($filterType === 'month') {
				// Filter by month
				$sql .= " WHERE MONTH(datetime) = MONTH('$dateFilter')";
			  } elseif ($filterType === 'week') {
				// Filter by week
				$weekStart = date('Y-m-d', strtotime('last Monday', strtotime($dateFilter)));
				$weekEnd = date('Y-m-d', strtotime('next Sunday', strtotime($dateFilter)));
				$sql .= " WHERE datetime >= '$weekStart' AND datetime <= '$weekEnd'";
			  } elseif ($filterType === 'date') {
				// Filter by specific date
				$sql .= " WHERE DATE(datetime) = '$dateFilter'";
			  }
			} else {
			  $sql .= " WHERE approval <> 'Approved' AND approval <> 'Denied'";
			}
			
			$sql .= " ORDER BY datetime DESC $limit";
			
			$result = mysqli_query($conn, $sql);
			
			if ($dateFilter != '') {
			  $sqlCount = "SELECT COUNT(*) as count FROM time_in WHERE DATE(datetime) = '$dateFilter'";
			} else {
			  $sqlCount = "SELECT COUNT(*) as count FROM time_in WHERE approval <> 'Approved' AND approval <> 'Denied'";
			}
			
			$resultCount = mysqli_query($conn, $sqlCount);
			$rowCount = mysqli_fetch_assoc($resultCount)['count'];
			$totalPages = ceil($rowCount / $rowsPerPage);
			
			?>
			<div class="card-listAcc">
				<ul class="box-info">
					<h1>TIME IN</h1>
				</ul>
			</div>
			<div class="filter-date">
				<form method="get">
  					<label for="filter_type">Filter by:</label>
  					<select name="filter_type" id="filter_type">
						<option value="year" <?php if ($filterType === 'year') echo 'selected'; ?>>Year</option>
    					<option value="month" <?php if ($filterType === 'month') echo 'selected'; ?>>Month</option>
    					<option value="week" <?php if ($filterType === 'week') echo 'selected'; ?>>Week</option>
    					<option value="date" <?php if ($filterType === 'date') echo 'selected'; ?>>Specific Date</option>
  					</select>
  
  					<label for="date">Date:</label>
  					<input type="date" name="date" id="date" value="<?php echo $dateFilter; ?>">
  
  					<input type="submit" value="Filter">
				</form>
			</div>
			<table>
				<thead>
					<tr>
					<th>Name</th>
						<th>Date and Time</th>
						<th>Location</th>
						<th>Photo</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($row = mysqli_fetch_assoc($result)) { ?>
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
						<td><?php echo $row['name']; ?></td>
						<td class="<?php echo $timeClass; ?>"><?php echo date('D, M d, Y h:i A', strtotime($row['datetime'])); ?></td>
						<td><?php echo $row['location']; ?></td>
						<td><a href="<?php echo $row['photo_loc']; ?>">View Photo</a></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

			<?php if ($totalPages > 1) { ?>
			<div class="page-Num">
				<?php if ($pageNum > 1) { ?>
				<a href="?page=<?php echo ($pageNum - 1); ?>&date=<?php echo $dateFilter; ?>">Prev</a>
				<?php } ?>
				<?php for ($i = 1; $i <= $totalPages; $i++) { ?>
				<?php if ($i == $pageNum) { ?>
				<strong><?php echo $i; ?></strong>
				<?php } else { ?>
				<a href="?page=<?php echo $i; ?>&date=<?php echo $dateFilter; ?>"><?php echo $i; ?></a>
				<?php } ?>
				<?php } ?>
				<?php if ($pageNum < $totalPages) { ?>
				<a href="?page=<?php echo ($pageNum + 1); ?>&date=<?php echo $dateFilter; ?>">Next</a>
				<?php } ?>
			</div>
			<?php } ?>
			<!--- Time in end --->

			<!--- Time out start --->
			<?php
			$rowsPerPage = 10;
			$pageNum = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
			$limit = 'LIMIT ' . (($pageNum - 1) * $rowsPerPage) . ', ' . $rowsPerPage;
			
			$filterType = isset($_GET['filter_type']) ? $_GET['filter_type'] : '';
			
			$sql = "SELECT name, datetime, overtime, hours, tasks, approval, token FROM time_out";
			
			// Apply the appropriate filter based on the selected option
			if ($dateFilter != '') {
			  if ($filterType === 'year') {
				// Filter by year
				$sql .= " WHERE YEAR(datetime) = YEAR('$dateFilter')";
			  } elseif ($filterType === 'month') {
				// Filter by month
				$sql .= " WHERE MONTH(datetime) = MONTH('$dateFilter')";
			  } elseif ($filterType === 'week') {
				// Filter by week
				$weekStart = date('Y-m-d', strtotime('last Monday', strtotime($dateFilter)));
				$weekEnd = date('Y-m-d', strtotime('next Sunday', strtotime($dateFilter)));
				$sql .= " WHERE datetime >= '$weekStart' AND datetime <= '$weekEnd'";
			  } elseif ($filterType === 'date') {
				// Filter by specific date
				$sql .= " WHERE DATE(datetime) = '$dateFilter'";
			  }
			} else {
			  $sql .= " WHERE approval = 'Reviewing'";
			}
			
			$sql .= " ORDER BY datetime DESC $limit";
			
			$result = mysqli_query($conn, $sql);
			
			if ($dateFilter != '') {
			  $sqlCount = "SELECT COUNT(*) as count FROM time_out WHERE DATE(datetime) = '$dateFilter'";
			} else {
			  $sqlCount = "SELECT COUNT(*) as count FROM time_out WHERE approval = 'Reviewing'";
			}
			
			$resultCount = mysqli_query($conn, $sqlCount);
			$rowCount = mysqli_fetch_assoc($resultCount)['count'];
			$totalPages = ceil($rowCount / $rowsPerPage);
			
			?>

			<div class="card-listAcc">
				<ul class="box-info">
					<h1 id='refresh'>TIME OUT</h1>
					<div id='marker'></div>
				</ul>
			</div>
			<div class="filter-date">
				<form method="get">
  					<label for="filter_type">Filter by:</label>
  					<select name="filter_type" id="filter_type">
						<option value="year" <?php if ($filterType === 'year') echo 'selected'; ?>>Year</option>
    					<option value="month" <?php if ($filterType === 'month') echo 'selected'; ?>>Month</option>
    					<option value="week" <?php if ($filterType === 'week') echo 'selected'; ?>>Week</option>
    					<option value="date" <?php if ($filterType === 'date') echo 'selected'; ?>>Specific Date</option>
  					</select>
  
  					<label for="date">Date:</label>
  					<input type="date" name="date" id="date" value="<?php echo $dateFilter; ?>">
  
  					<input type="submit" value="Filter">
				</form>
			</div>
			<table style="table-layout: fixed; width: 100%">
				<thead>
					<tr>
						<th>Status</th>
						<th>Name</th>
						<th>Date and Time</th>
						<th>Overtime</th>
						<th>Hours</th>
						<th>Tasks</th>
						<th>Approval</th>
					</tr>
					
				</thead>
				<tbody>
					<?php while ($row = mysqli_fetch_assoc($result)) { ?>
						<?php
						$time = strtotime($row['datetime']);
						$timeFormatted = date('H:i', $time);
						$token = $row['token'];
						$isHighlight = (
							($timeFormatted <= '07:45' || $timeFormatted >= '08:05') &&
							($timeFormatted <= '16:45' || $timeFormatted >= '17:05')
						  );
						$timeClass = $isHighlight ? 'late-time' : 'correct-time';
						?>
					<tr>
						<td><?php echo $row['approval']; ?></td>
						<td><?php echo $row['name']; ?></td>
						<td class="<?php echo $timeClass; ?>"><?php echo date('D, M d, Y h:i A', strtotime($row['datetime'])); ?></td>
						<td><?php echo $row['overtime']; ?></td>
						<td><?php echo $row['hours']; ?></td>
						<td style="word-wrap: break-word"><?php echo nl2br ($row['tasks']); ?></td>
						<?php
							echo "<td><button onclick=\"approveTimeOut('$token')\">Approve</button>";
							echo "  ";
							echo "<button onclick=\"disapproveTimeOut('$token')\">Reject</button></td>";
						?>


					</tr>
					<?php } ?>
				</tbody>
			</table>

			<?php if ($totalPages > 1) { ?>
			<div class="page-Num">
				<?php if ($pageNum > 1) { ?>
				<a href="?page=<?php echo ($pageNum - 1); ?>&date=<?php echo $dateFilter; ?>">Prev</a>
				<?php } ?>
				<?php for ($i = 1; $i <= $totalPages; $i++) { ?>
				<?php if ($i == $pageNum) { ?>
				<strong><?php echo $i; ?></strong>
				<?php } else { ?>
				<a href="?page=<?php echo $i; ?>&date=<?php echo $dateFilter; ?>"><?php echo $i; ?></a>
				<?php } ?>
				<?php } ?>
				<?php if ($pageNum < $totalPages) { ?>
				<a href="?page=<?php echo ($pageNum + 1); ?>&date=<?php echo $dateFilter; ?>">Next</a>
				<?php } ?>
			</div>
			<?php } ?>
			<!--- Time out end --->
			<form action="export_excel.php" method="POST">
			<div class="input-field">
				<input type="submit" class="submit" value="Export Attendance to Excel File">  
			</div>
			</form>

			<form action="filtered_export.php" method="get">
				<div class="filter-date">
					<label for="start_date">Start Date:</label>
					<input type="date" name="start_date" id="start_date" required>
					<label for="end_date">End Date:</label>
					<input type="date" name="end_date" id="end_date" required>
				</div>
				<div class="input-field">
					<input type="submit" class="submit" value="Export Filtered Attendance to Excel File">
				</div>
			</form>


			<!--- Intern remaining hours start --->
			<?php
			$int_rowsPerPage = 10;
			$int_pageNum = isset($_GET['int_page']) ? (int)$_GET['int_page'] : 1;
			$int_limit = 'LIMIT ' . (($int_pageNum - 1) * $int_rowsPerPage) . ', ' . $int_rowsPerPage;

			$int_sql = "SELECT name, department, start_date, hr_req, hr_ren, hr_left FROM int_info ORDER BY start_date DESC $int_limit";
			$int_result = mysqli_query($conn, $int_sql);

			$int_sqlCount = "SELECT COUNT(*) as count FROM int_info";
			$int_resultCount = mysqli_query($conn, $int_sqlCount);
			$int_rowCount = mysqli_fetch_assoc($int_resultCount)['count'];
			$int_totalPages = ceil($int_rowCount / $int_rowsPerPage);
			?>

			<div class="card-listAcc">
				<ul class="box-info">
					<h1>INTERN REMAINING HOURS</h1>
				</ul>
			</div>
			<table>
				<thead>
					<tr>
						<th>Name</th>
						<th>Department</th>
						<th>Start Date</th>
						<th>Hours Required</th>
						<th>Hours Rendered</th>
						<th>Hours Left</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($row = mysqli_fetch_assoc($int_result)) { ?>
					<tr>
						<td><?php echo $row['name']; ?></td>
						<td><?php echo $row['department']; ?></td>
						<td><?php echo date('D, M d, Y', strtotime($row['start_date'])); ?></td>
						<td><?php echo $row['hr_req']; ?></td>
						<td><?php echo $row['hr_ren']; ?></td>
						<td><?php echo $row['hr_left']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

			<?php if ($int_totalPages > 1) { ?>
			<div class="page-Num">
				<?php if ($int_pageNum > 1) { ?>
				<a href="?int_page=<?php echo ($int_pageNum - 1); ?>">Prev</a>
				<?php } ?>
				<?php for ($i = 1; $i <= $int_totalPages; $i++) { ?>
				<?php if ($i == $int_pageNum) { ?>
					<strong><?php echo $i; ?></strong>
				<?php } else { ?>
					<a href="?int_page=<?php echo $i; ?>"><?php echo $i; ?></a>
				<?php } ?>
				<?php } ?>
				<?php if ($int_pageNum < $int_totalPages) { ?>
				<a href="?int_page=<?php echo ($int_pageNum + 1); ?>">Next</a>
				<?php } ?>
			</div>
			<?php } ?>
			<!--- Intern remaining hours end --->

			<!--- manual time in start --->
			<form action="manual_timein.php" method="POST">
				<div class="card-listAcc">
					<ul class="box-info">
						<h1>MANUAL TIME IN</h1></a>
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
						<h4>Location</h4>
								<form method="POST" action="time_in.php">
								<select id="location" name="location" class="select-custom" required>
									<option value="">Choose location</option>
									<option value="WFH">Work From Home</option>
									<option value="Imus">Imus</option>
									<option value="Evia">Evia</option>
								</select>
						</div>
					<div class="input-field">
					<h4>Note: The time recorded will only be 8:00 am.</h4>
						<input type="reset" class="submitClear" value="Clear">
						<input type="submit" class="submit" value="Send">  
					</div>
					</ul>
				</div>
			</form>
			<!--- manual time in end --->

			<!--- manual time out start --->
			<form action="manual_timeout.php" method="POST">
				<div class="card-listAcc">
					<ul class="box-info">
						<h1>MANUAL TIME OUT</h1></a>
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
						<h4>Note: The time recorded will only be 5:00 pm.</h4>
						<input type="reset" class="submitClear" value="Clear">
						<input type="submit" class="submit" value="Send">  
					</div>
					</ul>
				</div>
			</form>
			<!--- manual time out end --->

			<!--- send notice start --->
			<form action="send_notice.php" method="POST">
				<div class="card-listAcc">
					<ul class="box-info">
						<h1>SEND NOTICE</h1></a>
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
						<h4>Type of Notice</h4>
						<select id="notice" name="notice" required class="select-custom">
							<option value="">Choose notice</option>
							<option value="Planned Leave">Planned Leave</option>
							<option value="School Initiated Leave">School Initiated Leave</option>
							<option value="Sick Leave">Sick Leave</option>
							<option value="Absence without Leave">Absence without Leave</option>
							<option value="Late (No Time in)">Late (No Time in)</option>
							<option value="Unidentified">Unindentified</option>
						</select>
					</div>
					<div class="input-field">
							<h4>Date</h4>
							<input type="date" class="input" id="date" name="date" required>
						</div>
					<div class="input-field">
						<h4>Remarks</h4>
						<input type="text" class="input" id="remarks" name="remarks" required>
					</div>
					<div class="input-field">
						<input type="reset" class="submitClear" value="Clear">
						<input type="submit" class="submit" value="Send">  
					</div>
					</ul>
				</div>
			</form>
			<!--- send notice end --->

			<!--- notices sent start --->
			<?php
			$rowsPerPage = 10;
			$pageNum = isset($_GET['not_page']) ? (int)$_GET['not_page'] : 1;
			$limit = 'LIMIT ' . (($pageNum - 1) * $rowsPerPage) . ', ' . $rowsPerPage;

			$sql = "SELECT name, type, date, remarks FROM notices ORDER BY date DESC $limit";
			$not_result = mysqli_query($conn, $sql);

			$sqlCount = "SELECT COUNT(*) as count FROM int_info";
			$resultCount = mysqli_query($conn, $sqlCount);
			$rowCount = mysqli_fetch_assoc($resultCount)['count'];
			$not_totalPages = ceil($rowCount / $rowsPerPage);
			?>

			<div class="card-listAcc">
				<ul class="box-info">
					<h1>NOTICES SENT</h1>
				</ul>
			</div>
			<table>
				<thead>
					<tr>
						<th>Name</th>
						<th>Type</th>
						<th>Date</th>
						<th>Remarks</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($row = mysqli_fetch_assoc($not_result)) { ?>
					<tr>
						<td><?php echo $row['name']; ?></td>
						<td><?php echo $row['type']; ?></td>
						<td><?php echo date('D, M d, Y', strtotime($row['date'])); ?></td>
						<td><?php echo $row['remarks']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

			<?php if ($not_totalPages > 1) { ?>
			<div class="page-Num">
				<?php if ($pageNum > 1) { ?>
				<a href="?not_page=<?php echo ($pageNum - 1); ?>">Prev</a>
				<?php } ?>
				<?php for ($i = 1; $i <= $not_totalPages; $i++) { ?>
				<?php if ($i == $pageNum) { ?>
					<strong><?php echo $i; ?></strong>
				<?php } else { ?>
					<a href="?not_page=<?php echo $i; ?>"><?php echo $i; ?></a>
				<?php } ?>
				<?php } ?>
				<?php if ($pageNum < $not_totalPages) { ?>
				<a href="?not_page=<?php echo ($pageNum + 1); ?>">Next</a>
				<?php } ?>
			</div>
			<?php } ?>
			<!--- notices sent end --->

			<!--- update intern hours start --->
			<div class="card-listAcc">
				<ul class="box-info">
					<h1>UPDATE INTERN HOURS</h1>
				</ul>
			</div>
			<form action="update_int_hours.php" method="POST">
				<div class="input-box">
					<ul class="box-info">
					<div class="input-field">
						<h4>Select a name</h4>
						<select id="name" name="int_name" onchange="fetchUserData(this.value)" required class="select-custom">
							<option value="">Select a name</option>
							<?php while ($row = mysqli_fetch_assoc($result_name)) { ?>
								<option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
							<?php } ?>
						</select><br>
					</div>
					<div class="input-field">
						<h4>Hours Required</h4>
						<input type="text" id="hr_req" name="hr_req" required><br>
					</div>
					<div class="input-field">
						<h4>Hours Rendered</h4>
						<input type="text" id="hr_ren" name="hr_ren" required><br>
					</div>
					<div class="input-field">
						<h4>Hours left</h4>
						<input type="text" id="hr_left" name="hr_left" required><br>
					</div>
					<div class="input-field">
						<input type="reset" class="submitClear" value="Clear">
						<input type="submit" class="submit" value="Update Hours">
					</div>
					</ul>
				</div>
			</form>
			<!--- update intern hours end --->
		</main>
	</section>
	<!-- CONTENT -->
	<script src="js/Dashboard.js"></script>
	<script src="js/navDropdown.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
  function approveTimeOut(token) {
    sendApprovalRequest(token, 'approve');
  }

  function disapproveTimeOut(token) {
    sendApprovalRequest(token, 'deny');
  }

  function sendApprovalRequest(token, action) {
  var xhr = new XMLHttpRequest();
  var element = document.getElementById('marker');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Handle the response here, if needed
      console.log(xhr.responseText);
      // Scroll to the specified element
      // Optionally, you can refresh the page after the approval request is sent
        location.reload();
		element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  };
  xhr.open('GET', 'toapprove.php?token=' + token + '&action=' + action, true);
  xhr.send();
  
}
</script>
	</body>
</html>