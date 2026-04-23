<?php
    session_start();
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include "connects.php";

    $page = 'admin_dash';
    $tab = 'admin';
    include_once('sidebar.php');

    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit();
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

    if ($position == "intern") {
        $result_text .= "<br>Hours Required: " .
            $row['hr_req'] . "<br>Hours Rendered: " .
            $row['hr_ren'] . "<br>Hours Left: " .
            $row['hr_left'];
    }

    // Get the current user's department
    $department = $_SESSION['department'];

    // Fetch all the announcements for the department
    $query = "SELECT * FROM announcement WHERE department='$department' OR department='All' ORDER BY date_created DESC";
    $result = mysqli_query($conn, $query);
    $announcements = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_close($conn);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/Dashboard.css">
    <link rel="stylesheet" href="css/admin_dash.css">
    <title>AMS | Dashboard</title>
</head>
<body>

<!-- SIDEBAR -->
<?php include_once 'sidebar.php'; ?>

<!-- CONTENT -->
<section id="content">
    <!-- NAVBAR -->
    <nav>
        <i class='bx bx-menu'></i>
        <h2><?php echo $_SESSION['username']; echo " | "; echo "AMS Admin"; echo "<br>";
            echo $row['position']; echo " | "; echo $row['department'];
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
        <div class="head-title">
            <table class="table-holder">
                <thead>
                <tr>
                    <th>
                        <h1 class="anncmnt-title">Announcement</h1>
                        <div class="pagination">
                            <a href="#" class="prev">&#10094;</a>
                            <a href="#" class="next">&#10095;</a>
                        </div>
                    </th>
                    <!-- ROW HEADER HERE-->
                </tr>
                </thead>
                <tbody class="tbody-holder">
                <tr>
                    <td>
                        <div class="carousel">
                            <?php foreach ($announcements as $announcement) : ?>
                                <div class="carousel-item">
                                    <h1 class="left"><?php echo $announcement['name']; ?></h1>
                                    <h2><?php echo $announcement['body']; ?></h2>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

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
                <h1>Dashboard</h1>
            </div>
        </div>

        <div class="grid-container">
            <div class="grid-item">
                <a href="summary_view.php">
                    <i class='bx bx-table logo-icon'></i>
                    <figcaption class="caption">Attendance Summary View</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="under_dev.html">
                    <i class='bx bx-windows logo-icon'></i>
                    <figcaption class="caption">Activities/DTRs</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="create_anncmnt.php">
                    <i class='bx bx-news logo-icon'></i>
                    <figcaption class="caption">Create Announcement</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="manual_inout.php">
                    <i class='bx bx-timer logo-icon'></i>
                    <figcaption class="caption">Manual In/Out</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="under_dev.html">
                    <i class='bx bx-bell logo-icon'></i>
                    <figcaption class="caption">Send Notice</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="under_dev.html">
                    <i class='bx bx-user-circle logo-icon'></i>
                    <figcaption class="caption">My Profile</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="add_userAcc.php">
                    <i class='bx bx-user-plus logo-icon'></i>
                    <figcaption class="caption">Account Creation</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="timein_timeout.php">
                    <i class='bx bx-time logo-icon'></i>
                    <figcaption class="caption">Time In and Out</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="under_dev.html">
                    <i class='bx bx-body logo-icon'></i>
                    <figcaption class="caption">CSK Org. Chart</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="under_dev.html">
                    <i class='bx bx-task logo-icon'></i>
                    <figcaption class="caption">Create Task</figcaption>
                </a>
            </div>
        </div>
    </main>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/Dashboard.js"></script>
<script src="js/navDropdown.js"></script>
<script src="js/annCarousel.js"></script>
<script src="js/summaryView.js"></script>

</body>
</html>
