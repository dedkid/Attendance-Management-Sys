<?php
date_default_timezone_set('Asia/Manila');
session_start();
include "connects.php";

$token = $_GET['token'];
$datetime = date('Y-m-d H:i:s');
$action = $_GET['action'];


$queryi = "SELECT datetime, name FROM time_in WHERE token = '$token' ORDER BY datetime DESC LIMIT 1";
$queryo = "SELECT datetime FROM time_out WHERE token = '$token' ORDER BY datetime DESC LIMIT 1";;
$resulto = mysqli_query($conn, $queryo);
$resulti = mysqli_query($conn, $queryi);

if(mysqli_num_rows($resulti) > 0) {

    $rowi = mysqli_fetch_assoc($resulti);
    $time_in = strtotime($rowi['datetime']);
    $rowo = mysqli_fetch_assoc($resulto);
    $time_out = strtotime($rowo['datetime']);
    $name = ($rowi['name']);

    $hours_diff = (($time_out - $time_in) / 3600) - 1;

    $dayOfWeek = date('w', $time_in); //for sunday OT
    if ($dayOfWeek === '0') {
        $hours_diff *= 1.5;
    }

    $query_int = "SELECT * FROM int_info WHERE name='$name'";
    $result_int = mysqli_query($conn, $query_int);
    $row_int = mysqli_fetch_assoc($result_int);
    
    $hr_ren = $row_int['hr_ren'] + $hours_diff;
    $hr_left = $row_int['hr_req'] - $hr_ren;
    
    $checkto = "SELECT hours FROM time_out WHERE token='$token' ORDER BY datetime DESC LIMIT 1";
    $resultto = mysqli_query($conn, $checkto);

    if(mysqli_num_rows($resultto) > 0) {
        
        $row_resultto = mysqli_fetch_assoc($resultto);

        
       if ($action === 'approve') {

            $intfo = "SELECT * FROM int_info WHERE name='$name'";
            $resultintfo = mysqli_query($conn, $intfo);
            $rowintfo = mysqli_fetch_assoc($resultintfo);

            $sqlintfo = "UPDATE int_info SET hr_ren='$hr_ren', hr_left='$hr_left' WHERE name='$name'";

            if (mysqli_query($conn, $sqlintfo)) {
                $sqlo_approval = "UPDATE time_out SET approval='Approved' WHERE token='$token'";
                $sqli_approval = "UPDATE time_in SET approval='Approved' WHERE token='$token'";
                mysqli_query($conn, $sqli_approval);
                mysqli_query($conn, $sqlo_approval);
            }
            else {
                echo "Error: time-out unsuccessfull";
            }

        } elseif ($action === 'deny') {
            $sql_check = "SELECT approval FROM time_out WHERE token='$token'";
            $result_check = mysqli_query($conn, $sql_check);
            $row_check = mysqli_fetch_assoc($result_check);
            $check = $row_check['approval'];

            if ($check === 'Approved') {
                $hr_ren = $row_int['hr_ren'] - $hours_diff;
                $hr_left = $row_int['hr_req'] + $hr_ren;

                $sqlintfo = "UPDATE int_info SET hr_ren='$hr_ren', hr_left='$hr_left' WHERE name='$name'";

            if (mysqli_query($conn, $sqlintfo)) {
                $sqlo_approval = "UPDATE time_out SET approval='Denied' WHERE token='$token'";
                $sqli_approval = "UPDATE time_in SET approval='Denied' WHERE token='$token'";
                mysqli_query($conn, $sqli_approval);
                mysqli_query($conn, $sqlo_approval);
            }
            else {
                echo "Error: time-out unsuccessfull";
            }

            }

            $sqlo_denial = "UPDATE time_out SET approval='Denied' WHERE token='$token'";
            $sqli_denial = "UPDATE time_in SET approval='Denied' WHERE token='$token'";
            mysqli_query($conn, $sqlo_denial);
            mysqli_query($conn, $sqli_denial);
        } else {
            echo 'An error occured';
        }

    } else {

        echo '<script>alert("No time out record found"); </script>';

    }

 } else {

     echo '<script>alert("No time in record found"); </script>';

 }

mysqli_close($conn);
exit;

?>