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

// 1. Fetch User Info
if ($position == "employee") {
    $query = "SELECT name, department, position, start_date, work_days, work_hrs FROM emp_info WHERE name='$name'";
} else {
    $query = "SELECT name, department, position, start_date, hr_req, hr_ren, hr_left, work_days, work_hrs FROM int_info WHERE name='$name'";
}

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$formatted_date = date('D, M d, Y', strtotime($row['start_date']));

// 2. Status check for buttons
$check_status = "SELECT status, role FROM users WHERE name='$name'";
$result_status = mysqli_query($conn, $check_status);
$row_status = mysqli_fetch_assoc($result_status);
$user_status = $row_status['status'];

$buti = ($user_status === 'in') ? 'disabled' : '';
$buto = ($user_status === 'out') ? 'disabled' : '';

// 3. Fetch LATEST records for today (The "Anti-Stuck" Logic)
$current_date = date('Y-m-d');

$check_time_in = "SELECT datetime FROM time_in WHERE DATE(datetime) = '$current_date' AND name='$name' ORDER BY datetime DESC LIMIT 1";
$result_time_in = mysqli_query($conn, $check_time_in);
$time_in_record = (mysqli_num_rows($result_time_in) > 0) ? mysqli_fetch_assoc($result_time_in)['datetime'] : "No record";

$check_time_out = "SELECT datetime FROM time_out WHERE DATE(datetime) = '$current_date' AND name='$name' AND datetime != '0000-00-00 00:00:00' ORDER BY datetime DESC LIMIT 1";
$result_time_out = mysqli_query($conn, $check_time_out);
$time_out_record = (mysqli_num_rows($result_time_out) > 0) ? mysqli_fetch_assoc($result_time_out)['datetime'] : "NA";

// 4. Intern variables
$hours_left_before = ($position === "intern") ? $row['hr_left'] : 0;
$hours_left_after = ($position === "intern") ? $row['hr_left'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/Dashboard.css">
    <link rel="stylesheet" href="css/reg_inout.css">
    <title>AMS | Dashboard</title>
</head>
<body>
    <section id="sidebar">
        <a href="reg_dash.php" class="brand">
            <img src="images/CSK Logo.png" alt="" class="logo">
            <span class="text">Attendance Management System</span>
        </a>
        <ul class="side-menu top">
            <li><a href="reg_dash.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
            <li class="active"><a href="reg_inout.php"><i class='bx bx-table'></i><span class="text">Time In/Out</span></a></li>
            <li><a href="int_emp_dtr_view.php"><i class='bx bx-windows'></i><span class="text">Tasks/DTRs</span></a></li>
        </ul>
    </section>

    <section id="content">
        <nav style="height: 100px;">
    <div class="left-nav" style="display: flex; align-items: center; gap: 24px; flex-grow: 1;">
        <i class='bx bx-menu'></i>
        <h2><?php echo $_SESSION['username'] . " | AMS Admin<br>" . $row['position'] . " | " . $row['department']; ?></h2>
    </div>
    
    <a href="logout.php" class="logout-btn">
        <i class='bx bxs-log-out-circle'></i>
        <span class="text">Logout</span>
    </a>
</nav>

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
            
            <div class="makerow-container">
                <div class="inout-container">
                    
                    <div class="time-in-container">
                        <div class="hours-column">
                            <?php if ($position == "intern"): ?>
                                <div class='hours-item'><strong>Hours Required:</strong> <?php echo $row['hr_req']; ?></div>
                                <div class='hours-item'><strong>Hours Remaining:</strong> <?php echo $hours_left_before; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="time-column">
                            <?php if ($user_status !== 'in') : ?>
                                <a href="time_in.php" <?php echo $buti; ?> style="text-decoration:none; color:inherit;">
                            <?php endif; ?>
                                <div class="time-logo"><i class="uil uil-clock-eight"></i></div>
                                <div>TIME IN</div>
                            <?php if ($user_status !== 'in') : ?></a><?php endif; ?>
                        </div>

                        <div class="status-column">
                            <?php if ($time_in_record !== "No record"): ?>
                                <div class="status">Status: Successfully</div>
                                <div class="status-time">
                                    Time IN: <?php echo date('h:i:s A', strtotime($time_in_record)); ?><br>
                                    <?php echo date('M/d/Y', strtotime($time_in_record)); ?>
                                </div>
                            <?php else: ?>
                                <div class="status">Status: No Record Yet</div>
                                <div class="status-time">Time IN: No Record Yet</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="time-out-container">
                        <div class="hours-column">
                            <?php if ($position == "intern"): ?>
                                <div class='hours-item'><strong>Hours Remaining:</strong> <?php echo $hours_left_after; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="time-column">
                            <form method="POST" action="time_out.php">
                                <button type="submit" class="time-button" <?php echo $buto; ?>>
                                    <i class="uil uil-clock-five"></i>
                                </button>
                                <div>TIME OUT</div>
                        </div>

                        <div class="status-column">
                            <?php if ($time_out_record !== "NA"): ?>
                                <div class="status">Status: Successfully</div>
                                <div class="status-time">
                                    Time OUT: <?php echo date('h:i:s A', strtotime($time_out_record)); ?><br>
                                    <?php echo date('M/d/Y', strtotime($time_out_record)); ?>
                                </div>
                            <?php else: ?>
                                <div class="status">Status: No Record Yet</div>
                                <div class="status-time">Time OUT: No Record Yet</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="input-field-text">
                        <textarea id="taskstext" name="tasks" class="input" minlength="30" placeholder="Task here" required></textarea>
                    </div>
                    </form> </div>

                <div class="utility-column">
                    <form action="reg_inout_export.php" method="get">
                        <div class="row"><strong>Filter Date</strong></div>
                        <div class="row">
                            <label for="start_date">Start Date:</label>
                            <input type="date" name="start_date" id="start_date" required>
                        </div>
                        <div class="row">
                            <label for="end_date">End Date:</label>
                            <input type="date" name="end_date" id="end_date" required>
                        </div>
                        <div class="row">
                            <input type="hidden" name="name" value="<?php echo $name; ?>">
                            <input type="submit" class="submit" value="Export My Attendance">
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </section>

    <script src="js/Dashboard.js"></script>
    <script src="js/summaryView.js"></script>
    <script src="js/navDropdown.js"></script>
</body>
</html>