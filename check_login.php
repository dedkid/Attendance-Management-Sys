<?php
    session_start();

    include "connects.php";
    

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $position = $row['position'];
        $status = $row['status'];

        // Retrieve additional user information using the new query
        $info_sql = "SELECT u.name, u.role, u.position, COALESCE(ei.department, ii.department) AS department
                    FROM users u
                    LEFT JOIN emp_info ei ON u.name = ei.name AND u.position = 'Employee'
                    LEFT JOIN int_info ii ON u.name = ii.name AND u.position = 'Intern'
                    WHERE u.name = '$name'";

        $info_result = mysqli_query($conn, $info_sql);
        $info_row = mysqli_fetch_assoc($info_result);

        $role = $info_row['role'];
        $department = $info_row['department'];

        if ($role == 'admin') {
            header("Location: admin_dash.php");
            $_SESSION['username'] = $name;
            $_SESSION['position'] = $position;
            $_SESSION['role'] = $role;
            $_SESSION['department'] = $department;
            exit();
        } elseif ($role == 'regular') {
            header("Location: reg_dash.php");
            $_SESSION['username'] = $name;
            $_SESSION['position'] = $position;
            $_SESSION['role'] = $role;
            $_SESSION['department'] = $department;
            exit();
        }
    } else {
        echo '<script>alert("Invalid email or password"); window.location.href = "index.php";</script>';
        return;
    }

    mysqli_close($conn);
?>
