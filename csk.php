<?php

    session_start();

	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

    include 'connects.php';
	$page = 'csk';
    $tab = 'switch_csk';
    include_once('sidebar.php');


    if(isset($_SESSION['username'])) {
    //do nothing
    } else {
       header('Location: index.php');
       exit;
    }

	$name = $_SESSION['username'];
    $position = $_SESSION['position'];

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

	
	
    
?>




<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Boxicons -->
		<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
		<!-- My CSS -->
		<link rel="stylesheet"href="css/csk-edit.css">
		<link rel="stylesheet"href="css/Dashboard.css">
		<title>AMS | Company</title>
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
			</div><br>

			<div class="creation">
				<ul class="creation-info">
					<div class="creation-field">
                        <text>Chrisimm Sentimo Kumon Corp. Management</text>
					</div>
				</ul>
			</div ><br><br>

			
		
            <div class="container">
                    <div class="left">
                        <div class="org">
                            <p>
                                <br>
                                • &nbsp; Chief Executive Officer <br>
                                • &nbsp; COO <br>
                                • &nbsp; Executive Assistant <br>
                                • &nbsp; Administrator <br>
                                • &nbsp; Operations Manager <br>
                                • &nbsp; IT Supervisor <br>
                                • &nbsp; Marketing Supervisor <br>
                                • &nbsp; HRD Supervisor <br>
                                • &nbsp; Accounting Supervisor <br>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="box">
                    <h1>Employees</h1>
                    <p>
                        <?php 


    // 2. Fetch all users from 'users' table and group them by position
    $position_groups = [];
    $org_query = "SELECT name, position FROM users ORDER BY name ASC";
    $org_result = mysqli_query($conn, $org_query);

    if ($org_result) {
        while ($user_row = mysqli_fetch_assoc($org_result)) {
            // Grouping: $position_groups['employee'][] = 'Name'
            $position_groups[strtolower($user_row['position'])][] = $user_row['name'];
        }
    }
                        if(!empty($position_groups['employee'])) {
                            foreach($position_groups['employee'] as $empName) {
                                echo htmlspecialchars($empName) . "<br>";
                            }
                        } else {
                            echo "No employees found.";
                        }

						mysqli_close($conn);
                        ?>
                    </p>
                </div>

		
		</main>
		
	<script src="js/Dashboard.js"></script>
	<script src="js/navDropdown.js"></script>
	<script src="js/summaryView.js"></script>
	</body>
</html>