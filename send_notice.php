<?php
    session_start();
    include "connects.php";

    $name = $_POST['name'];
    $notice = $_POST['notice'];
    $date = date('Y-m-d', strtotime($_POST["date"]));
    $remarks = $_POST["remarks"];
    $sql = "INSERT INTO notices (name, type, date, remarks) VALUES ('$name', '$notice', '$date', '$remarks')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Notice sent to $name.'); window.location.href = 'attendances.php';</script>";
    return;
    } else {
    echo "Error sending notice";
    }

    mysqli_close($conn);
?>