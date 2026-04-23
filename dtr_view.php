<?php


session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include 'connects.php';

$page = 'dtr_view';
$tab = 'attendance';
include_once('sidebar.php');

if (isset($_SESSION['username'])) {
    //do nothing
} else {
    header('Location: index.php');
    exit;
}

$name = $_SESSION['username'];
$position = $_SESSION['position'];

if ($position == "employee") {
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

if ($position == "intern") {
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
            <i class='bx bx-menu'></i>
            <h2><?php echo $_SESSION['username'];
                echo " | ";
                echo "AMS Admin";
                echo "<br>";
                echo $row['position'];
                echo " | ";
                echo $row['department'];
                ?></h2>

            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
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
                    <h1>Activities and DTRs</h1>
                </div>
            </div>
            <?php
            $departmentFilter = isset($_GET['department']) ? $_GET['department'] : '';
            $name_search = isset($_GET['name_search']) ? $_GET['name_search'] : '';
            $fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : '';
            $toDate = isset($_GET['to_date']) ? $_GET['to_date'] : '';

            // Query to fetch all users
            $all_users_sql = "SELECT u.name, COALESCE(ei.department, ii.department) AS department

FROM users u
LEFT JOIN emp_info ei ON u.name = ei.name
LEFT JOIN int_info ii ON u.name = ii.name";


            // Check if date range is provided
            if (!empty($fromDate) && !empty($toDate)) {
                // Query to filter users based on date range
                $date_filter_sql = "SELECT u.name, COALESCE(ei.department, ii.department) AS department,

        CASE WHEN tim.datetime IS NULL THEN 'N/A' ELSE DATE_FORMAT(tim.datetime, '%Y-%m-%d') END AS time_in_date,
        CASE WHEN tom.datetime IS NULL THEN 'N/A' ELSE DATE_FORMAT(tom.datetime, '%Y-%m-%d') END AS time_out_date
    FROM users u
    LEFT JOIN emp_info ei ON u.name = ei.name
    LEFT JOIN int_info ii ON u.name = ii.name
    LEFT JOIN (
        SELECT name, MAX(datetime) AS datetime
        FROM time_in
        WHERE DATE(datetime) BETWEEN '$fromDate' AND '$toDate'
        GROUP BY name
    ) tim ON u.name = tim.name
    LEFT JOIN (
        SELECT name, MAX(datetime) AS datetime
        FROM time_out
        WHERE DATE(datetime) BETWEEN '$fromDate' AND '$toDate'
        GROUP BY name
    ) tom ON u.name = tom.name
    WHERE tim.datetime IS NOT NULL AND tom.datetime IS NOT NULL";


                if ($fromDate > $toDate) {
                    echo "The dates seem to be invalid.";
                }

                // Add additional filters based on name or department
                if (!empty($departmentFilter)) {
                    $date_filter_sql .= " AND COALESCE(ei.department, ii.department) = '$departmentFilter'";

                    if (!empty($name_search)) {
                        $date_filter_sql .= " AND (u.name LIKE '%$name_search%' OR ei.name LIKE '%$name_search%' OR ii.name LIKE '%$name_search%')";
                    }
                } elseif (!empty($name_search)) {
                    $date_filter_sql .= " AND (u.name LIKE '%$name_search%' OR ei.name LIKE '%$name_search%' OR ii.name LIKE '%$name_search%')";
                }

                $dtr_result = mysqli_query($conn, $date_filter_sql);
            } else {
                // No date range provided, apply filters based on name or department
                $dtr_sql = $all_users_sql;

                if (!empty($departmentFilter)) {
                    $dtr_sql .= " WHERE COALESCE(ei.department, ii.department) = '$departmentFilter'";

                    if (!empty($name_search)) {
                        $dtr_sql .= " AND (u.name LIKE '%$name_search%' OR ei.name LIKE '%$name_search%' OR ii.name LIKE '%$name_search%')";
                    }
                } elseif (!empty($name_search)) {
                    $dtr_sql .= " WHERE (u.name LIKE '%$name_search%' OR ei.name LIKE '%$name_search%' OR ii.name LIKE '%$name_search%')";
                }

                $dtr_result = mysqli_query($conn, $dtr_sql);
            }


            $departmentOptions = array('IT', 'HR', 'Accounting', 'Marketing', 'Admin', 'Management');

            ?>

            <form class="filter" method="get">
                <div class="filter-department">
                    <label for="from_date">From:</label>
                    <input type="date" name="from_date" id="from_date" value="<?php echo $fromDate; ?>">
                    <label for="to_date">To:</label>
                    <input type="date" name="to_date" id="to_date" value="<?php echo $toDate; ?>">
                    <select name="department" id="department">
                        <option value="">All Departments</option>
                        <?php
                        foreach ($departmentOptions as $option) {
                            $selected = ($departmentFilter == $option) ? 'selected' : '';
                            echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                        }
                        ?>
                    </select>
                    <input placeholder="Name Search" type="text" name="name_search" id="name-search" value="<?php echo $name_search; ?>">

                </div>
                <button class="filter-btn" type="submit">Apply Filter</button>
            </form>

            <div class="tg-wrap">
                <table style="width: 100%" class="tg">
                    <tbody>
                        <tr>
                            <th class="tg-0pky">Name</th>
                            <th class="tg-0pky">Department</th>
                            <th class="tg-0pky">From</th>
                            <th class="tg-0pky">To</th>
                            <th class="tg-0pky">DTR File</th>
                        </tr>
                        <?php
                        while ($row = mysqli_fetch_assoc($dtr_result)) {
                            echo '<tr>';
                            echo '<td class="tg-0pky">' . $row['name'] . '</td>';
                            echo '<td id="dep-col" class="tg-0pky">' . $row['department'] . '</td>';
                            echo '<td id="date-col" class="tg-0pky">' . $fromDate . '</td>';
                            echo '<td id="date-col" class="tg-0pky">' . $toDate . '</td>';
                            echo '<td id="but-col" class="tg-0pky"><button class="btn-down"><a href="dtr_export.php?name=' . urlencode($row['name']) . '&start_date=' . urlencode($fromDate) . '&end_date=' . urlencode($toDate) . '">Download File</a><button></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <script src="js/Dashboard.js"></script>
            <script src="js/summaryView.js"></script>
            <script src="js/navDropdown.js"></script>

</body>
</html>

