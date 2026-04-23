<?php
    session_start();
    require_once('connects.php');

    $position = $_SESSION['position'];

    if ($position === "employee") {
        header("Location: emp_leave_ot.php");
        exit();
    } elseif ($position === "intern") {
        header("Location: reg_leave_ot.php");
        exit();
    } else {
        echo "Unknown position. Please contact the administrator.";
    }
?>
