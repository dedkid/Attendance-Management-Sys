<?php
    session_start();
    include "connects.php";

    $name = $_POST['name'];
    $deletionSuccessful = false;

    $sql_users = "DELETE FROM users WHERE name = '$name'";
    $conn->query($sql_users);
    if ($conn->affected_rows > 0) {
        echo "Records deleted successfully from the 'users' table.<br>";
        $deletionSuccessful = true;
    } else {
        echo "No records found in the 'users' table for the specified name.<br>";
    }

    $sql_time_in = "DELETE FROM time_in WHERE name = '$name'";
    $conn->query($sql_time_in);
    if ($conn->affected_rows > 0) {
        echo "Records deleted successfully from the 'time_in' table.<br>";
        $deletionSuccessful = true;
    } else {
        echo "No records found in the 'time_in' table for the specified name.<br>";
    }

    $sql_time_out = "DELETE FROM time_out WHERE name = '$name'";
    $conn->query($sql_time_out);
    if ($conn->affected_rows > 0) {
        echo "Records deleted successfully from the 'time_out' table.<br>";
        $deletionSuccessful = true;
    } else {
        echo "No records found in the 'time_out' table for the specified name.<br>";
    }

    $sql_leaves = "DELETE FROM leaves WHERE name = '$name'";
    $conn->query($sql_leaves);
    if ($conn->affected_rows > 0) {
        echo "Records deleted successfully from the 'leaves' table.<br>";
        $deletionSuccessful = true;
    } else {
        echo "No records found in the 'leaves' table for the specified name.<br>";
    }

    $sql_int_info = "DELETE FROM int_info WHERE name = '$name'";
    $conn->query($sql_int_info);
    if ($conn->affected_rows > 0) {
        echo "Records deleted successfully from the 'int_info' table.<br>";
        $deletionSuccessful = true;
    } else {
        echo "No records found in the 'int_info' table for the specified name.<br>";
    }

    $sql_emp_info = "DELETE FROM emp_info WHERE name = '$name'";
    $conn->query($sql_emp_info);
    if ($conn->affected_rows > 0) {
        echo "Records deleted successfully from the 'emp_info' table.<br>";
        $deletionSuccessful = true;
    } else {
        echo "No records found in the 'emp_info' table for the specified name.<br>";
    }

    mysqli_close($conn);

    if ($deletionSuccessful) {
        echo '<script>alert("User info deleted."); window.location.href = "accounts.php";</script>';
    } else {
        echo '<script>alert("No records found for the specified name."); window.location.href = "accounts.php";</script>';
    }
?>
