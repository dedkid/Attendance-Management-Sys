<?php

    session_start();

	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

    include 'connects.php';

    $page = 'timein_timeout';
    $tab = 'admin';
    include_once('sidebar.php');

    if(isset($_SESSION['username'])) {
    //do nothing
    } else {
       header('Location: index.php');
       exit;
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


<script>
    function formatTime(date) {
      var hours = date.getHours();
      var minutes = date.getMinutes();
      var seconds = date.getSeconds();
      var ampm = hours >= 12 ? 'PM' : 'AM';

      hours = hours % 12;
      hours = hours ? hours : 12;
      hours = hours.toString().padStart(2, '0');
      minutes = minutes.toString().padStart(2, '0');
      seconds = seconds.toString().padStart(2, '0');

      return hours + ":" + minutes + ":" + seconds + ' ' + ampm;
    }

    function formatDate(date) {
      const options = { month: 'long', day: 'numeric', year: 'numeric' };
      return date.toLocaleDateString(undefined, options);
    }

    

</script>


<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    

   
	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="css/dtr_view.css">

	<title>AMS | Employee and Intern Management</title>
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
            <div class="head-title">
            <div class="left">
					<h1>View Time In/Out</h1>
				</div>
			</div>
   
            <?php
$positionFilter = isset($_GET['position']) ? $_GET['position'] : 'employee';
$departmentFilter = isset($_GET['department']) ? $_GET['department'] : '';

if ($positionFilter === 'intern') {
    $dtr_sql = "SELECT u.name, ii.department, TIME(t.datetime) AS time_in, TIME(o.datetime) AS time_out, ii.hr_req, ii.hr_ren, o.hours
                FROM users u
                JOIN int_info ii ON u.name = ii.name
                LEFT JOIN time_in t ON u.name = t.name AND DATE(t.datetime) = CURDATE()
                LEFT JOIN time_out o ON u.name = o.name AND DATE(o.datetime) = CURDATE()";
} else {
    $dtr_sql = "SELECT u.name, ei.department, TIME(t.datetime) AS time_in, TIME(o.datetime) AS time_out, o.hours
                FROM users u
                JOIN emp_info ei ON u.name = ei.name
                LEFT JOIN time_in t ON u.name = t.name AND DATE(t.datetime) = CURDATE()
                LEFT JOIN time_out o ON u.name = o.name AND DATE(o.datetime) = CURDATE()";
}

if (!empty($departmentFilter)) {
    $dtr_sql .= " WHERE department = '$departmentFilter'";
}

$dtr_result = mysqli_query($conn, $dtr_sql);

if (!$dtr_result) {
    // Query execution failed, display the error message and terminate the script
    die('Error: ' . mysqli_error($conn));
}

$departmentOptions = array('IT', 'HR', 'Accounting', 'Marketing', 'Admin', 'Management');
?>

<form class="filter" method="get">
    <div class="filter-department">
        <label for="position">Position:</label>
        <button type="submit" name="position" value="employee" <?php if ($positionFilter === 'employee') echo 'class="active"'; ?>>Employee</button>
        <button type="submit" name="position" value="intern" <?php if ($positionFilter === 'intern') echo 'class="active"'; ?>>Intern</button>
        <label for="department">Department:</label>
        <select name="department" id="department">
            <option value="">All Departments</option>
            <?php
            foreach ($departmentOptions as $option) {
                $selected = ($departmentFilter == $option) ? 'selected' : '';
                echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
            }
            ?>
        </select>
    </div>
</form>

<div class="tg-wrap">
    <table style="width: 100%" class="tg">
        <tbody>
            <tr class="firstrow">
                <th class="tg-0pky">Name</th>
                <th class="tg-0pky">Department</th>
                <th class="tg-0pky">Date</th>
                <th class="tg-0pky">Time in</th>
                <th class="tg-0pky">Time out</th>
                <?php if ($positionFilter === 'intern') { ?>
                <th class="tg-0pky">Hours required</th>
                <th class="tg-0pky">Hours rendered</th>
                <th class="tg-0pky">Date Hours</th>
                <th class="tg-0pky">Overtime</th>
                <?php } elseif ($positionFilter === 'employee') { ?>
                <th class="tg-0pky">Hours</th>
                <?php } ?>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($dtr_result)) {
                echo '<tr>';
                echo '<td class="tg-0pky">' . $row['name'] . '</td>';
                echo '<td class="tg-0pky">' . $row['department'] . '</td>';
                echo '<td class="tg-0pky">' . date('Y-m-d') . '</td>';
                echo '<td class="tg-0pky">' . substr($row['time_in'], 0, 5) . '</td>';
                echo '<td class="tg-0pky">' . substr($row['time_out'], 0, 5) . '</td>';
                echo '<td class="tg-0pky">' . substr($row['time_out'], 0, 5) . '</td>';
               

                if ($positionFilter === 'intern') {
                    echo '<td class="tg-0pky">' . $row['hr_req'] . '</td>';
                    echo '<td class="tg-0pky">' . $row['hr_ren'] . '</td>';
                    echo '<td class="tg-0pky">';
                    
                    if (!empty($row['hours'])) {
                        echo $row['hours'];
                    }
                    
                    echo '</td>';
                } elseif ($positionFilter === 'employee') {
                    echo '<td class="tg-0pky">';
                    
                    if (!empty($row['hours'])) {
                        echo $row['hours'];
                    }
                    
                    echo '</td>';
                }
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<form action="export_excel.php" method="POST">
			<div class="input-field">
				<input type="submit" class="submit-excel" value="Export as Excel File">  
			</div>
			</form>

	<script src="js/Dashboard.js"></script>
    <script src="js/summaryView.js"></script>
    <script src="js/navDropdown.js"></script>
    

</body>
</html>