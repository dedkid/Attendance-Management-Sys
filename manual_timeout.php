<?php
date_default_timezone_set('Asia/Manila');
    session_start();
    include "connects.php";

    $name = $_POST['name'];
    $datetime = date('Y-m-d 17:00:00');

    $check_status = "SELECT status FROM users WHERE name='$name'";
    $result_status = mysqli_query($conn, $check_status);

    if (mysqli_num_rows(($result_status)) > 0) {
        $row_status = mysqli_fetch_assoc($result_status);
        $user_status = ($row_status['status']);

        if ($user_status === 'in') {

            $query = "SELECT datetime, token
            FROM time_in
            WHERE name='$name'
            ORDER BY id DESC LIMIT 1";
            $result = mysqli_query($conn, $query);

            if(mysqli_num_rows($result) > 0) {

                $row = mysqli_fetch_assoc($result);
                $time_in = strtotime($row['datetime']);
                $time_out = strtotime($datetime);
                $token = ($row['token']);
        
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

    	        $sqlo="UPDATE time_out SET approval='Reviewing', name='$name', datetime='$datetime', overtime=0, hours='$hours_diff', tasks='none' WHERE token = '$token'";
                $sqli="UPDATE time_in SET approval='Reviewing' WHERE token='$token'";
                $sqlstat = "UPDATE users SET status='out' WHERE name='$name'";
        
    	        if (mysqli_query($conn, $sqlo)) {
        	        // $sql_int = "UPDATE int_info SET hr_ren='$hr_ren', hr_left='$hr_left' WHERE name='$name'";
        	        // if (mysqli_query($conn, $sql_int)) {
                    mysqli_query($conn, $sqlstat);
                    mysqli_query($conn, $sqli);
                    header("Refresh:0; url=attendances.php");
                    echo "<script>alert('Timed-out for $name');</script>";
        	        // } else {
                    // 	echo "Error doing time out";
        	        // }
                } else {
        	        echo "Time out not recorded";
                }

            } else {
                echo '<script>alert("No time in record found"); window.location.href = "attendances.php";</script>';
            }

        } elseif ($user_status === 'out'){
            echo '<script>alert("This user is still Timed-out. Please time-in the user first."); window.location.href = "attendances.php";</script>';
        } else {
            echo '<script>alert("Error."); window.location.href = "attendances.php";</script>';
        }

    }

    
    mysqli_close($conn);
?>