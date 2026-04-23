<?php
    session_start();
    include "connects.php";

    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "UPDATE users SET password = '$password' WHERE email = '$email'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Password updated for $email'); window.location.href = 'accounts.php';</script>";
    } else {
        echo "Error updating password";
    }

    mysqli_close($conn);
?>