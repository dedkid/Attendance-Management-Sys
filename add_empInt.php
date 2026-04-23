<?php
    session_start();
    include "connects.php";

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $position = $_POST['position'];
    $work_days = $_POST['wrk_days'];
    $work_hrs = $_POST['wrk_hrs'];
    $school = $_POST['school'];
    $hrs_req = $_POST['hrs_req'];
    $department = $_POST['department'];
    $dateStart = $_POST['dateStarted'];
    if (str_contains(strtolower($position), "intern")) {
        $pos = "intern";
    } else {
        $pos = "employee";
    }
    $sql = "INSERT INTO users (email, password, name, role, position) VALUES ('$email', '$password', '$name', '$role', '$pos')";
    if (str_contains(strtolower($position), "intern")) {
        $sqlinfo = "INSERT INTO int_info (name, department, start_date, work_days, work_hrs, hr_req) VALUES ('$name', '$department', '$dateStart', '$work_days', '$work_hrs', '$hrs_req')";
    } else {
        $sqlinfo = "INSERT INTO emp_info (name, department, position, start_date, work_days, work_hrs) VALUES ('$name', '$department', 
        '$position', '$dateStart', '$work_days', '$work_hrs')";
    }
    
    if ($conn->query($sqlinfo) === TRUE && $conn->query($sql) === TRUE) {
    echo '<script>alert("User added. Notify intern/employee that they now have website access."); window.location.href = "add_userAcc.php";</script>';
    return;
    } else {
        echo "Error in creating account.";
    }

    mysqli_close($conn);
?>