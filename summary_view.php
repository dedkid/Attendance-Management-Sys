<?php

session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include 'connects.php';

$page = 'summary_view';
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
    <link rel="stylesheet" href="css/summaryView.css">
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
            <h2><?php
            echo $_SESSION['username']; echo " | "; echo "AMS Admin"; echo "<br>";
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

            <?php
            $users_sql = "SELECT name FROM users";
            $users_result = mysqli_query($conn, $users_sql);

            // Store all names from the users table
            $allNames = array();
            while ($row = mysqli_fetch_assoc($users_result)) {
                $allNames[] = $row['name'];
            }
            mysqli_free_result($users_result);

            $timein_sql = "SELECT u.name, COALESCE(ei.department, ii.department) AS department, COALESCE(ei.work_hrs, ii.work_hrs) AS work_hrs, u.position, t.datetime AS timein, o.datetime AS timeout, n.type, n.date
            FROM users u
            LEFT JOIN emp_info ei ON u.name = ei.name AND u.position = 'Employee'
            LEFT JOIN int_info ii ON u.name = ii.name AND u.position = 'Intern'
            LEFT JOIN time_in t ON u.name = t.name
            LEFT JOIN time_out o ON u.name = o.name
            LEFT JOIN notices n ON u.name = n.name
            WHERE DAYOFWEEK(t.datetime) BETWEEN 2 AND 7
            ORDER BY CASE COALESCE(ei.department, ii.department)
                WHEN 'IT' THEN 1
                WHEN 'Marketing' THEN 2
                WHEN 'HR' THEN 3
                WHEN 'Accounting' THEN 4
                WHEN 'Admin' THEN 5
                ELSE 6
            END, timein ASC";

            $timein_result = mysqli_query($conn, $timein_sql);

            // Store unique dates
            $dates = [];

            // Get the current month and year
            $currentMonth = date('m');
            $currentYear = date('Y');

            // Set the start and end dates of the month
            $startDate = new DateTime("$currentYear-$currentMonth-01");
            $endDate = (new DateTime("$currentYear-$currentMonth-01"))->modify('last day of this month');

            // Loop through each day from the start to end date
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $dayOfWeek = $currentDate->format('N');

                // Check if the day of the week is from Monday to Saturday (1 to 6)
                if ($dayOfWeek >= 1 && $dayOfWeek <= 6) {
                    $dates[] = $currentDate->format('Y-m-d');
                }

                // Move to the next day
                $currentDate->modify('+1 day');
            }

            // Track names
            $nameTracker = array();

            // Store time in data for each name
            $timeinData = array();

            while ($row = mysqli_fetch_assoc($timein_result)) {
                $name = $row['name'];
                $date = $row['timein'] ? date('Y-m-d', strtotime($row['timein'])) : '';

                if (!in_array($date, $dates) && $date !== '') {
                    $dates[] = $date;
                }

                // Store time in and time out data for each name and date

                if (!isset($timeinData[$name])) {
                    $timeinData[$name] = array(
                        'department' => $row['department'],
                        'position' => $row['position'],
                        'work_hrs' => $row['work_hrs'],
                        'data' => array()
                    );
                }

                if (!isset($timeinData[$name]['data'][$date])) {
                    $timeinData[$name]['data'][$date] = array(
                        'timein' => $row['timein'],
                        'timeout' => $row['timeout']
                    );
                }


                // Track the printed name
                if (!in_array($name, $nameTracker)) {
                    $nameTracker[] = $name;
                }

                // Check if the name has an associated notice with date and type
                $noticeDate = $row['date'];
                $noticeType = $row['type'];
                if ($noticeDate && $noticeType) {
                    // Store the notice data in the noticeData array
                    if (!isset($timeinData[$name]['data'][$noticeDate])) {
                        $timeinData[$name]['data'][$noticeDate] = array();
                    }

                    // Set the values in Ti and To based on the notice type
                    if ($noticeType == 'School Initiated Leave') {
                        $timeinData[$name]['data'][$noticeDate]['timein'] = 'SIL';
                        $timeinData[$name]['data'][$noticeDate]['timeout'] = 'SIL';
                    } elseif ($noticeType == 'Sick Leave') {
                        $timeinData[$name]['data'][$noticeDate]['timein'] = 'SL';
                        $timeinData[$name]['data'][$noticeDate]['timeout'] = 'SL';
                    } elseif ($noticeType == 'Absence without Leave') {
                        $timeinData[$name]['data'][$noticeDate]['timein'] = 'ABW';
                        $timeinData[$name]['data'][$noticeDate]['timeout'] = 'ABW';
                    } elseif ($noticeType == 'Late (No Time in)') {
                        $timeinData[$name]['data'][$noticeDate]['timein'] = 'L';
                        $timeinData[$name]['data'][$noticeDate]['timeout'] = 'L';
                    } elseif ($noticeType == 'Unidentified') {
                        $timeinData[$name]['data'][$noticeDate]['timein'] = '?';
                        $timeinData[$name]['data'][$noticeDate]['timeout'] = '?';
                    } elseif ($noticeType == 'Planned Leave') {
                        $timeinData[$name]['data'][$noticeDate]['timein'] = 'PL';
                        $timeinData[$name]['data'][$noticeDate]['timeout'] = 'PL';
                    }

                    // Add the notice date to the dates array if it doesn't exist
                    if (!in_array($noticeDate, $dates)) {
                        $dates[] = $noticeDate;
                    }
                }
            }
            sort($dates);
            mysqli_free_result($timein_result);
            ?>

            <div class="tg-wrap">
                <table style="width: 100%" class="tg">
                    <tbody>
                        <tr>
                            <th class="tg-0pky"></th>
                            <th class="tg-0pky"></th>
                            <th class="tg-0pky"></th>
                            <th class="tg-0pky"></th>
                            <?php
                            // Display unique dates in table header
                            foreach ($dates as $date) {
                                echo '<th class="tg-0pky" colspan="2">' . $date . '</th>';
                            }
                            ?>
                        </tr>
                        <tr>
                            <!-- column-name is responsible for the unsightly color change -->
                            <th class="tg-0pky">Name</th>
                            <th class="tg-0pky">Department</th>
                            <th class="tg-0pky">Position</th>
                            <th class="tg-0pky">Schedule</th>
                            <?php
                            // Display corresponding headers for each date
                            foreach ($dates as $date) {
                                echo '<th class="tg-0pky">Ti</th>';
                                echo '<th class="tg-0pky">To</th>';
                            }
                            ?>
                        </tr>
                        <?php
                        // Display data rows
                        foreach ($allNames as $name) {
                            echo '<tr>';
                            echo '<td class="tg-0pky">' . $name . '</td>';

                            // Check if the name exists in the timeinData array
                            if (isset($timeinData[$name])) {
                                echo '<td class="tg-0pky">' . $timeinData[$name]['department'] . '</td>';
                                echo '<td class="tg-0pky">' . $timeinData[$name]['position'] . '</td>';
                                echo '<td class="tg-0pky">' . $timeinData[$name]['work_hrs'] . '</td>';

                                // Display corresponding data for each date
                                foreach ($dates as $date) {
                                    // This tries to follow the whole path. If any part is missing, it returns 
                                    $timein = $timeinData[$name]['data'][$date]['timein'] ?? '';
                                    $timeout = $timeinData[$name]['data'][$date]['timeout'] ?? '';

                                    // Check if the name has multiple timeouts in a single day
                                    if ($timeout !== '' && $timein === '') {
                                        $latestTimeout = '';
                                        foreach ($timeinData[$name]['data'][$date] as $entry) {
                                            if ($entry['timeout'] !== '') {
                                                $latestTimeout = $entry['timeout'];
                                            }
                                        }
                                        $timein = $latestTimeout === '' ? 'L' : '';
                                    }

                                    // Check if the Ti value is "PL" and replace the cell value with "PL" instead of filling it with green color
                                    if ($timein == 'PL') {
                                        echo '<td class="tg-0pky">' . $timein . '</td>';
                                    } elseif ($timein == 'SIL') {
                                        echo '<td class="tg-0pky">' . $timein . '</td>';
                                    } elseif ($timein == 'SL') {
                                        echo '<td class="tg-0pky">' . $timein . '</td>';
                                    } elseif ($timein == 'ABW') {
                                        echo '<td class="tg-0pky red-bg">' . '</td>';
                                    } elseif ($timein == 'L') {
                                        echo '<td class="tg-0pky">' . $timein . '</td>';
                                    } elseif ($timein == '?') {
                                        echo '<td class="tg-0pky green-bg">' . '</td>';
                                    } else {
                                        echo '<td class="tg-0pky' . ($timein ? ' green-bg' : '') . '"></td>';
                                    }

                                    // Check if the To value is "PL" and replace the cell value with "PL" instead of filling it with green color
                                    if ($timeout == 'PL') {
                                        echo '<td class="tg-0pky">' . $timeout . '</td>';
                                    } elseif ($timeout == 'SIL') {
                                        echo '<td class="tg-0pky">' . $timeout . '</td>';
                                    } elseif ($timeout == 'SL') {
                                        echo '<td class="tg-0pky">' . $timeout . '</td>';
                                    } elseif ($timeout == 'ABW') {
                                        echo '<td class="tg-0pky red-bg">' . '</td>';
                                    } elseif ($timeout == 'L') {
                                        echo '<td class="tg-0pky green-bg">' . '</td>';
                                    } elseif ($timeout == '?') {
                                        echo '<td class="tg-0pky">' . $timeout . '</td>';
                                    } else {
                                        echo '<td class="tg-0pky' . ($timeout ? ' green-bg' : '') . '"></td>';
                                    }
                                }
                            } else {
                                // If the name does not have any data, display empty cells
                                for ($i = 0; $i < count($dates); $i++) {
                                    echo '<td class="tg-0pky"></td>';
                                    echo '<td class="tg-0pky"></td>';
                                }
                            }

                            echo '</tr>';
                        }

                        ?>
                    </tbody>
                </table>
            </div>

            <!-- end of table div -->

            <form action="export_excel.php" method="POST">
                <div class="input-field">
                    <input type="submit" class="submit-excel" value="Export Summary View to Excel File">
                </div>
            </form>

            <!-- CONTENT -->
            <script src="js/Dashboard.js"></script>
            <script src="js/summaryView.js"></script>
            <script src="js/navDropdown.js"></script>

        </main>
    </section>

</body>

</html>
