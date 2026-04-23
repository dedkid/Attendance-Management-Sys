<?php
    session_start();

	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");
	
    include 'connects.php';
	include "access_control.php";

	$page = 'leaves';
	$tab = 'attendance';
	include_once('sidebar.php');

    if(isset($_SESSION['username'])) {
    //do nothing
    } else {
       header('Location: index.php');
       exit;
    }	

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

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="css/Leaves.css">
	<title>AMS | Leaves</title>
</head>
<body>
	<!-- SIDEBAR -->
	<?php include_once 'sidebar.php'; ?>

	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
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
					<h1>Leaves</h1>
				</div>
			</div>

			<?php
			$rowsPerPage = 10;
			$pageNum = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
			$limit = 'LIMIT ' . (($pageNum - 1) * $rowsPerPage) . ', ' . $rowsPerPage;

			if ($dateFilter != '') {
				$sql = "SELECT name, leave_type, date_req, date_app, status, remarks FROM leaves WHERE DATE(date_req) = '$dateFilter' ORDER BY date_req DESC $limit";
			} else {
				$sql = "SELECT name, leave_type, date_req, date_app, status, remarks FROM leaves ORDER BY date_req DESC $limit";
			}

			$result = mysqli_query($conn, $sql);
			if ($dateFilter != '') {
				$sqlCount = "SELECT COUNT(*) as count FROM leaves WHERE DATE(date_req) = '$dateFilter'";
			} else {
				$sqlCount = "SELECT COUNT(*) as count FROM leaves";
			}
			$resultCount = mysqli_query($conn, $sqlCount);
			$rowCount = mysqli_fetch_assoc($resultCount)['count'];
			$totalPages = ceil($rowCount / $rowsPerPage);
			?>

			<div class="card-listAcc">
				<ul class="box-info">
					<h1>LIST OF LEAVES</h1>
				</ul>
			</div>
			<div class="filter-date">
			<form method="get">
				<label for="date">Filter by date:</label>
				<input type="date" name="date" id="date" value="<?php echo $int_dateFilter; ?>">
				<input type="submit" value="Filter">
			</form>
			</div>
			<table>
				<thead>
					<tr>
						<th>Name</th>
						<th>Leave Type</th>
						<th>Date Requested</th>
						<th>Date Approved</th>
						<th>Status</th>
						<th>Remarks</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($row = mysqli_fetch_assoc($result)) { ?>
					<tr>
						<td><?php echo $row['name']; ?></td>
						<td><?php echo $row['leave_type']; ?></td>
						<td><?php echo date('D, M d, Y', strtotime($row['date_req'])); ?></td>
						<td><?php echo date('D, M d, Y', strtotime($row['date_app'])); ?></td>
						<td><?php echo $row['status']; ?></td>
						<td><?php echo $row['remarks']; ?></td>
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

			<form action="record_leave.php" method="post"> 
				<div class="card-listAcc">
					<div class="box-info">
						<h1>RECORD LEAVE</h1>
					</div>
				</div>
				<div class="input-box">
					<ul class="box-info">
					<div class="input-field">
						<h4>Name</h4>
						<select id="name" name="name" required class="select-custom">
							<option value="">Choose name</option>
							<?php
							foreach ($combinedNames as $name) {
								echo "<option value='$name'>$name</option>";
							}
							?>
						</select>
					</div>
					<div class="input-field">
						<h4>Leave Type</h4>
						<select id="leave_type" name="leave_type" required class="select-custom">
							<option value="">Choose leave type</option>
							<option value="Planned Leave">Planned Leave</option>
							<option value="School Initiated Leave">School Initiated Leave</option>
							<option value="Emergency Leave">Emergency Leave</option>
							<option value="Sick Leave">Sick Leave</option>
							<option value="Birthday Leave">Birthday Leave</option>
							<option value="Vacation Leave">Vacation Leave</option>
						</select><br>
					</div>
					<div class="input-field">
						<h4>Date Requested</h4>
						<input type="datetime-local" class="input" id="date_req" name="date_req" required>
					</div>
					<div class="input-field">
						<h4>Date Approved</h4>
						<input type="datetime-local" class="input" id="date_app" name="date_app" required>
					</div>
					<div class="input-field">
						<h4>Status</h4>
						<select id="status" name="status" required class="select-custom">
							<option value="">Choose status</option>
							<option value="Approved">Approved</option>
							<option value="Rejected">Rejected</option>
						</select><br>
					</div>
					<div class="input-field">
						<h4>Remarks</h4>
						<input type="text" class="input" id="remarks" name="remarks" required>
					</div>
					<div class="input-field">
						<input type="reset" class="submitClear" value="Clear">
						<input type="submit" class="submit-excel" value="Record Leave">
					</div>
					</ul>
				</div>
			</form>
		</main>
    </div>
	</section>
	<!-- CONTENT -->
	<script src="js/Dashboard.js"></script>
	<script src="js/navDropdown.js"></script>

</body>
</html>