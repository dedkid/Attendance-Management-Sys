<?php
    session_start();
    include "connects.php";

    $name = $_POST['name'];
    $type_leave = $_POST['leave_type'];
    $date_req = date('Y-m-d H:i:s', strtotime($_POST["date_req"]));
    $date_app = date('Y-m-d H:i:s', strtotime($_POST["date_app"]));
    $status = $_POST['status'];
    $remarks = $_POST["remarks"];
    $sql = "INSERT INTO leaves (name, leave_type, date_req, date_app, status, remarks) VALUES ('$name', '$type_leave', '$date_req', '$date_app', '$status', '$remarks')";
    if ($conn->query($sql) === TRUE) {
    echo '<script>alert("Leave recorded."); window.location.href = "leaves.php";</script>';
    return;
    } else {
    echo "Error recording leave";
    }

    mysqli_close($conn);
?>