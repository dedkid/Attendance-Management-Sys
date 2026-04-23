<?php
    session_start();

	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

    include 'connects.php';

    if(isset($_SESSION['username'])) {
    //do nothing
    } else {
       header('Location: index.php');
       exit;
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
	<link rel="stylesheet" href="css/summaryView.css">
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
            <a href="#">
                <i class='bx bx-clipboard'></i>
                <span class="text">Attendance</span>
            </a>
        </li>
        <div class="dropdown">
            <li>
                <a href="summary_view.php">
                    <i class='bx bx-clipboard'></i>
                    <span class="text">Summary View</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-clipboard'></i>
                    <span class="text">Activities / DTR's </span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-clipboard'></i>
                    <span class="text">Manual in / Out </span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-clipboard'></i>
                    <span class="text">Leave Tagging </span>
                </a>
            </li>
        </div>

			<li>
				<a href="users.php">
					<i class='bx bxs-calendar-check'></i>
					<span class="text">Management</span>
				</a>
			</li>
			<li  class="active">
				<a href="accounts.php">
					<i class='bx bxs-user-account' ></i>
					<span class="text">Time In / Out</span>
				</a>
			</li>
			<li>
				<a href="leaves.php">
					<i class='bx bxs-calendar-exclamation'></i>
					<span class="text">My Profile</span>
				</a>
			</li>
            <li>
				<a href="leaves.php">
					<i class='bx bxs-calendar-exclamation'></i>
					<span class="text">CSK</span>
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
            <div>
			<h3><?php echo $_SESSION['username']; ?></h3>
            <h3><?php echo $_SESSION['position']; ?></h3>
            </div>
            <div>
            <h3><?php echo $_SESSION['role']; ?></h3>
            <h3><?php echo $_SESSION['department']; ?></h3>
            </div>
		</nav>

		<!-- MAIN -->
		<main>
			<div class="input-box">
				<ul class="box-info">
					<div class="input-field">
						<div class="date-time">
							<h1><span id="live-time"></span></h1>
						</div>
					</div>
				</ul>
			</div><br>

            <h1>My Notices </h1>

<!-- Table HTML -->
<div class="tg-wrap">
    <table style="width: 100%" class="tg">
        <tbody>
            <tr>
                <th class="tg-0pky column-notices">My Notices</th>
                <th class="tg-0pky">Date</th>
                <th class="tg-0pky">From</th>
            </tr>
            <?php
            // Query to retrieve notices for the current user
            $notices_sql = "SELECT type, date, `from` FROM notices WHERE name = '$name'";
            $notices_result = mysqli_query($conn, $notices_sql);

            if (!$notices_result) {
                // Query execution failed, display the error message and terminate the script
                die('Error: ' . mysqli_error($conn));
            }

            while ($notice_row = mysqli_fetch_assoc($notices_result)) {
                echo '<tr>';
                echo '<td class="tg-0pky column-name">' . $notice_row['type'] . '</td>';
                echo '<td class="tg-0pky">' . $notice_row['date'] . '</td>';
                echo '<td class="tg-0pky">' . $notice_row['from'] . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

	<script src="js/Dashboard.js"></script>
    <script src="js/summaryView.js"></script>
    

</body>
</html>