<?php
    session_start();
    include "connects.php";

	date_default_timezone_set('Asia/Manila');

    $location = $_POST['location'];
    $name = $_POST['name'];
    $datetime = date('Y-m-d 8:00:00');

    $token = generateToken($conn);

    
    $check_status = "SELECT status FROM users WHERE name='$name'";
    $result_status = mysqli_query($conn, $check_status);

    if (mysqli_num_rows(($result_status)) > 0) {

        $row_status = mysqli_fetch_assoc($result_status);
        $user_status = ($row_status['status']);

        if ($user_status === 'out') {

        $sqli = "INSERT INTO time_in (name, datetime, photo_loc, location, token) VALUES ('$name', '$datetime', 'photo_loc', '$location', '$token')";
        $sqlo = "INSERT INTO time_out (name, token) VALUES ('$name', '$token')";
	    $sqlstat = "UPDATE users SET status='in' WHERE name='$name'";

        if ($conn->query($sqli) === TRUE && $conn->query($sqlo) === TRUE && $conn->query($sqlstat) === TRUE) {
            mysqli_query($conn, $sqlstat);
            header("Refresh:0; url=attendances.php");
            echo "<script>alert('Timed-in for $name');</script>";
            return;
        } else {
            echo "Error doing time in";
        }

    } elseif ($user_status === 'in') {
        echo '<script>alert("This user is still Timed-in. Please time-out the user first."); window.location.href = "attendances.php";</script>';
    } else {
        echo '<script>alert("Error."); window.location.href = "attendances.php";</script>';
    }

}

    mysqli_close($conn);

    function generateToken($conn) {
		// Generate the token
		$token = bin2hex(random_bytes(8));
		
		// Return the generated token
		return $token;
	}
?>