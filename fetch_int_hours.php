<?php
    session_start();
    include "connects.php";

    if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $sql = "SELECT hr_req, hr_ren, hr_left FROM int_info WHERE name = '$name'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    }
    }

    $conn->close();
?>
