<?php
    session_start();

	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

    include 'connects.php';

    $page = 'create_anncmnt';
    $tab = 'mngmt';
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

    // repost the announcement
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $date = date('Y-m-d');
        $sql = "UPDATE announcement SET date_created='$date' WHERE id='$id'";
        $repost=mysqli_query($conn, $sql);
    }

    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // retrives announcement data
    $query2 = "SELECT id, date_created, name, department, body FROM announcement ORDER BY date_created DESC";
    $result2 = mysqli_query($conn, $query2);
    $row2 = mysqli_fetch_assoc($result2);

    mysqli_data_seek($result2, 0); // move pointer to first row
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
    <link rel="stylesheet" href="css/create_anncmnt.css">
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
            <div class="date-time left title">
                <h1>Announcement Creation</h1>
            </div>
        </div>
        <form action="add_anncmnt.php" method="POST">   <!-- Sends data to add_anncmnt.php file-->
            <div class="input-box">
                <ul class="box-info">
                    <div class="form-header">
                        <div class="selector">
                            <h4 style="padding-right: 10px">To:</h4>
                            <select id="dept" name="dept" required class="select-custom">
                                <option value="">Choose department</option>
                                <option value="Admin">Admin</option>
                                <option value="HR">HR</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Acccounting">Accounting</option>
                                <option value="IT">IT</option>
                                <option value="All">All</option>
                            </select><br>
                        </div>
                        <div class="input-field">
                            <?php
                                echo '<span>From: ' . $name. '</span>';
                            ?>
                        </div>
                    </div>
                    <div class="input-field wrapper">    
                        <textarea type="text" class="input" id="body" name="body"
                        required
                        autocomplete="off"
                        rows="20"
                        maxlength="200"
                        placeholder="Announcement Body"
                        ></textarea>
                    </div>

                    <div class="send-btn">
                        <input type="submit" class="submit" value="Send">
                    </div>
                </ul>
            </div>
        </form>

        <div class="tg-wrap">
            <table style="width: 100%" class="tg">
                <tbody>
                    <tr>
                        <th class="tg-0pky column-name" style="width: 70%">Previous Created Announcement</th>
                        <th class="tg-0pky column-name">Date</th>
                        <th class="tg-0pky column-name">Repost</th>
                    </tr>
                    <?php
                    while ($row2 = mysqli_fetch_assoc($result2)) {
                        $date = $row2['date_created'];
                        $formatted_date = date('D, M d, Y', strtotime($date));
                        echo "
                        <tr>
                            <td 'class='tg-0pky column-name'>" . $row2['body'] . "</td>
                            <td 'class='tg-0pky column-name'>" .  $formatted_date . "</td>
                            <td>
                                <a href='create_anncmnt.php?id=" . $row2['id'] ."' class='btn'> Send </a>
                            </td>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </div>
</section>
	<script src="js/Dashboard.js"></script>
    <script src="js/summaryView.js"></script>
    <script src="js/navDropdown.js"></script>

     <script>

        function formatDate(dateNow) {
            const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
            return dateNow.toLocaleDateString(undefined, options);
        }

        function updateTime() {
            var dateNow = new Date();
            var hours = dateNow.getHours();
            var minutes = dateNow.getMinutes();
            var seconds = dateNow.getSeconds();
            var ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12;

            hours = hours.toString().padStart(2, '0');
            minutes = minutes.toString().padStart(2, '0');
            seconds = seconds.toString().padStart(2, '0');

            var currentDate = formatDate(dateNow);
            document.getElementById("live-time").textContent = hours + ":" + minutes + ":" + seconds + ' ' + ampm + " | " + currentDate;
        }

        updateTime(); // Call the function initially to display the time immediately
        setInterval(updateTime, 1000); // Call the function every second to update the time
  </script>
    
    

</body>
</html>