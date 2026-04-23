<?php
    session_start();
    include "connects.php";

    $name = $_POST['name'];
    $dept = $_POST['dept'];
    $start_date = date('Y-m-d', strtotime($_POST["start_date"]));
    $work_days = $_POST['work_days'];
    $work_hrs = $_POST['work_hrs'];
    $hr_req = $_POST['hr_req'];
    $sql = "INSERT INTO int_info (name, department, start_date, work_days, work_hrs, hr_req) VALUES ('$name', '$dept', '$start_date', '$work_days', '$work_hrs', '$hr_req')";
    if ($conn->query($sql) === TRUE) {
    echo '<script>alert("Intern info successfully added."); window.location.href = "users.php";</script>';
    return;
    } else {
    echo "Error in adding intern information";
    }

    mysqli_close($conn);
?>