<?php
    session_start();
    include "connects.php";

    $name = $_POST['name'];
    $dept = $_POST['dept'];
    $position = $_POST['position'];
    $work_days = $_POST['work_days'];
    $work_hrs = $_POST['work_hrs'];
    $start_date = date('Y-m-d', strtotime($_POST["start_date"]));
    $sql = "INSERT INTO emp_info (name, department, position, start_date, work_days, work_hrs) VALUES ('$name', '$dept', '$position', '$start_date', '$work_days', '$work_hrs')";
    if ($conn->query($sql) === TRUE) {
    echo '<script>alert("Employee info successfully added."); window.location.href = "users.php";</script>';
    return;
    } else {
    echo "Error in adding employee information";
    }

    mysqli_close($conn);
?>