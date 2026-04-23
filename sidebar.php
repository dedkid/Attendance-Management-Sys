<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    
	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
    <link rel="stylesheet" href="css/send_notification.css">
</head>
<body>
    <section id="sidebar">
        <a href="admin_dash.php" class="brand">
            <img src="images/CSK Logo.png" alt="" class="logo">
            <span class="text">Attendance Management System</span>
        </a>
        <ul class="side-menu top">
            <li class="<?php if ($page == 'admin_dash'){ echo 'active';}?>">
                <a href="admin_dash.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a id="switch" style="cursor: default;">
                    <i class='bx bx-clipboard'></i>
                    <span class="text">Attendance</span>
                </a>
            </li>
            <div class="dropdown" style="<?php if ($tab != 'attendance'){
                    echo "display: none;";
                    }?>">
                <li class="<?php if ($page == 'summary_view'){ echo 'active';}?>">
                    <a href="summary_view.php">
                        <i class='bx bx-table'></i>
                        <span class="text">Summary View</span>
                    </a>
                </li>
                <li class="<?php if ($page == 'dtr_view'){ echo 'active';}?>">
                    <a href="dtr_view.php">
                        <i class='bx bx-windows'></i>
                        <span class="text">Activities/DTRs</span>
                    </a>
                </li>
                <li class="<?php if ($page == 'manual_inout'){ echo 'active';}?>">
                    <a href="manual_inout.php">
                        <i class='bx bx-timer'></i>
                        <span class="text">Manual In/Out</span>
                    </a>
                </li>
                <li class="<?php if ($page == 'leaves'){ echo 'active';}?>">
                    <a href="leaves.php">
                        <i class='bx bx-purchase-tag'></i>
                        <span class="text">Leave Tagging</span>
                    </a>
                </li>
            </div>

                <li>
                    <a id="switch_mngmt" style="cursor: default;">
                        <i class='bx bx-list-check'></i>
                        <span class="text">Management</span>
                    </a>
                </li>
                <div class="dropdown_mngmt" style="<?php if ($tab != 'mngmt'){
                    echo "display: none;";
                    }?>">
                <li class="<?php if ($page == 'create_anncmnt'){
                    echo 'active';
                    }?>">
                    <a href="create_anncmnt.php">
                        <i class='bx bx-news'></i>
                        <span class="text">Create Announcement</span>
                    </a>
                </li>

                <li class="<?php if ($page == 'send_notification'){
                    echo 'active';
                    }?>">
                    <a href="send_notification.php">
                        <i class='bx bx-bell'></i>
                        <span class="text">Send Notification</span>
                    </a>
                </li>
                <li>
                    <a href="under_dev.html">
                        <i class='bx bx-task'></i>
                        <span class="text">Send Task</span>
                    </a>
                </li>
                <li class="<?php if ($page == 'add_userAcc'){
                    echo 'active';
                    }?>">
                    <a href="add_userAcc.php">
                        <i class='bx bx-user-plus'></i>
                        <span class="text">Create Account</span>
                    </a>
                </li>
            </div>

                <li class="<?php if ($page == 'timein_timeout'){
                    echo 'active';
                    }?>">
                    <a href="timein_timeout.php">
                        <i class='bx bx-time' ></i>
                        <span class="text">View Time In / Out</span>
                    </a>
                </li>

                <li class="<?php if ($page == 'admin_inout'){
                    echo 'active';
                    }?>">
                    <a href="admin_inout.php">
                        <i class='bx bx-timer' ></i>
                        <span class="text">My Time In / Out</span>
                    </a>
                </li>
                <li>
                    <a href="under_dev.html">
                        <i class='bx bx-user-circle'></i>
                        <span class="text">My Profile</span>
                    </a>
                </li>
                <li>
                    <a id="switch_csk" style="cursor: default;">
                        <i class='bx bx-globe'></i>
                        <span class="text">Company</span>
                    </a>
                </li>

                <div class="dropdown_csk" style="<?php if ($tab != 'switch_csk'){
                    echo "display: none;";
                    }?>">
                <li>
                    <a href="under_dev.html">
                        <i class='bx bx-group'></i>
                        <span class="text">Account List</span>
                    </a>
                </li>
                <li class="<?php if ($page == 'csk'){
                    echo 'active';
                    }?>">
                    <a href="csk.php">
                        <i class='bx bx-body'></i>
                        <span class="text">Organization</span>
                    </a>
                </li>
            </div>
            </ul>
            
    </section>
</body>
</html>