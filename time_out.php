<?php
date_default_timezone_set('Asia/Manila');

session_start();
include "connects.php";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$name = $_SESSION['username'];
$datetime = date('Y-m-d H:i:s');
$tasks = isset($_POST["tasks"]) ? mysqli_real_escape_string($conn, $_POST["tasks"]) : "No tasks reported";

// 1. Get user profile and current status
$check_status = "SELECT status, role, position FROM users WHERE name='$name'";
$result_status = mysqli_query($conn, $check_status);

if ($result_status && mysqli_num_rows($result_status) > 0) {
    $row_status = mysqli_fetch_assoc($result_status);
    $user_status = $row_status['status'];
    $position_status = $row_status['position'];
    $role_status = $row_status['role'];

    // Only allow time-out if they are currently "in"
    if ($user_status === "in") {
        
        // 2. Find the most recent 'time_in' to get the start time and the token
        $query = "SELECT datetime, token FROM time_in WHERE name='$name' ORDER BY datetime DESC LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $time_in = strtotime($row['datetime']);
            $time_out = strtotime($datetime);
            $token = $row['token'];

            // 3. Calculate Hours
            $raw_hours = ($time_out - $time_in) / 3600;
            
            // Deduction Logic: Subtract 1 hour (lunch) only if shift is > 5 hours
            // This prevents 4-hour interns from being penalized
            $hours_diff = ($raw_hours > 5) ? ($raw_hours - 1) : $raw_hours;

            // 4. Sunday Overtime Multiplier (1.5x)
            $dayOfWeek = date('w', $time_in); 
            if ($dayOfWeek == '0') {
                $hours_diff *= 1.5;
            }
            
            // Round to 2 decimal places for DB cleanliness
            $hours_diff = round($hours_diff, 2);

            // 5. Prepare SQL Updates
            // Update the specific time_out row using the unique token
            $sqli = "UPDATE time_out SET 
                        approval='Reviewing', 
                        datetime='$datetime', 
                        overtime='0', 
                        hours='$hours_diff', 
                        tasks='$tasks' 
                     WHERE token = '$token'";
            
            // Update the matching time_in row
            $sqlo = "UPDATE time_in SET approval='Reviewing' WHERE token = '$token'";
            
            // Update user status back to 'out'
            $sqlstat = "UPDATE users SET status='out' WHERE name='$name'";

            // 6. Execute Main Updates
            if (mysqli_query($conn, $sqli)) {
                mysqli_query($conn, $sqlstat);
                mysqli_query($conn, $sqlo);

                // 7. Intern-Specific Logic (Hour Tracking)
                if ($position_status === "intern") {
                    if ($hours_diff >= 4) {
                        // Fetch current totals for calculation
                        $query_int = "SELECT hr_ren, hr_req FROM int_info WHERE name='$name'";
                        $res_int = mysqli_query($conn, $query_int);
                        
                        if ($row_int = mysqli_fetch_assoc($res_int)) {
                            $hr_ren = $row_int['hr_ren'] + $hours_diff;
                            $hr_left = $row_int['hr_req'] - $hr_ren;
                            
                            $sql_int = "UPDATE int_info SET hr_ren='$hr_ren', hr_left='$hr_left' WHERE name='$name'";
                            mysqli_query($conn, $sql_int);
                        }

                        header("Refresh:0; url=reg_inout.php");
                        echo "<script>alert('Timed-out successfully. $hours_diff hours credited.');</script>";
                    } else {
                        // Alert if minimum hours not met
                        header("Refresh:0; url=reg_inout.php");
                        echo "<script>alert('Shift too short ($hours_diff hrs). Minimum 4 hours required for credit. Contact your supervisor.');</script>";
                    }
                } 
                // 8. Regular Employee/Admin Logic
                elseif ($position_status === "employee") {
                    $url = ($role_status === "admin") ? "admin_inout.php" : "reg_inout.php";
                    header("Refresh:0; url=$url");
                    echo "<script>alert('Timed-out successfully');</script>";
                }
            } else {
                echo "Error updating time-out record: " . mysqli_error($conn);
            }

        } else {
            echo "Error: Corresponding Time-In record not found.";
        }
        
    } elseif ($user_status === "out") {
        $url = ($role_status === "admin") ? "admin_inout.php" : "reg_inout.php";
        header("Refresh:0; url=$url");
        echo "<script>alert('You are already timed-out! Please time-in first.');</script>";
    } else {
        echo "Invalid user status detected.";
    }

} else {
    echo "Error: User data could not be retrieved.";
}

mysqli_close($conn);
exit;
?>