<?php
    session_start();
    include "connects.php";

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $position = $_POST['position'];
    $sql = "INSERT INTO users (email, password, name, role, position) VALUES ('$email', '$password', '$name', '$role', '$position')";
    if ($conn->query($sql) === TRUE) {
    echo '<script>alert("User added. Notify intern/employee that they now have website access."); window.location.href = "accounts.php";</script>';
    return;
    } else {
    echo "Error in adding account";
    }

    mysqli_close($conn);
?>