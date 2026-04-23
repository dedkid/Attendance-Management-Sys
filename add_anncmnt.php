<?php
    session_start();
    include "connects.php";

    $uniqueId = bin2hex(random_bytes(32));
    $name = $_SESSION['username'];
    $dept = $_POST['dept'];
    $date = date('Y-m-d');
    $body = $_POST['body'];
    $sql = "INSERT INTO announcement (date_created, name, department, body) VALUES ('$date','$name', '$dept','$body')";
    if ($conn->query($sql) === TRUE) {
    echo '<script>alert("Announcement info successfully added."); window.location.href = "create_anncmnt.php";</script>';
    return;
    } else {
    echo "Error in adding announcement";
    }

    mysqli_close($conn);
?>