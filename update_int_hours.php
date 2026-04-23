<?php
    session_start();
    include "connects.php";

    $name = $_POST['int_name'];
    $hr_req = $_POST['hr_req'];
    $hr_ren = $_POST['hr_ren'];
    $hr_left = $_POST['hr_left'];

    $sql = "UPDATE int_info SET hr_req = '$hr_req', hr_ren = '$hr_ren', hr_left = '$hr_left' WHERE name = '$name'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Hours updated for $name'); window.location.href = 'attendances.php';</script>";
    } else {
        echo "Error updating hours";
    }
    $conn->close();
?>
