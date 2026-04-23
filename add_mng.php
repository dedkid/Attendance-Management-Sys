<?php
    session_start();
    include "connects.php";

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $position = $_POST['position'];
    if (str_contains(strtolower($position), "intern")) {
        echo '<script>alert("The position entered is intern. If the account is for an intern, please create the account with the Employee/Intern Account Creation."); window.location.href = "add_userAcc.php";</script>';
    }
    $sql = "INSERT INTO users (email, password, name, role, position) 
            VALUES ('$email', '$password', '$name', '$role', 'employee')";
    $sqlinfo = "INSERT INTO emp_info (name, department, position, start_date, work_days, work_hrs) 
                VALUES ('$name', 'N/A', '$position', '2019-01-01', 'N/A', 'N/A')";
    
    if ($conn->query($sqlinfo) === TRUE && $conn->query($sql) === TRUE) {
    echo '<script>alert("User added. Notify intern/employee that they now have website access."); window.location.href = "add_userAcc.php";</script>';
    return;
    } else {
        echo "Error in creating account.";
    }

    mysqli_close($conn);
?>