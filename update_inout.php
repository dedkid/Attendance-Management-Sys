<?php
// Include the connects.php file
require_once 'connects.php';

// Check if the form is submitted and the necessary fields are present
if (isset($_POST['name']) && isset($_POST['time_in']) && isset($_POST['time_out'])) {
    // Retrieve the submitted form data
    $name = $_POST['name'];

    $time_in = date('h:i', strtotime($_POST['time_in'])); // Convert to 12-hour format
    $time_out = date('h:i', strtotime($_POST['time_out'])); // Convert to 12-hour format


    // Extract the date from the time_in or time_out value
    $date = date('Y-m-d', strtotime($time_in)); // Assuming the date format is 'Y-m-d'

    // Check if the time entry already exists for the specified name and date
    $check_sql = "SELECT * FROM time_in WHERE name = '$name' AND DATE(datetime) = '$date'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // Update the existing time entry

        // Update the time_in value in the time_in table
        $update_in_sql = "UPDATE time_in SET datetime = '$date $time_in' WHERE name = '$name'";
        $update_in_result = mysqli_query($conn, $update_in_sql);

        // Update the time_out value in the time_out table
        $update_out_sql = "UPDATE time_out SET datetime = '$date $time_out' WHERE name = '$name'";
        $update_out_result = mysqli_query($conn, $update_out_sql);

        if ($update_in_result && $update_out_result) {
            // Time values updated successfully
            echo 'Time values updated successfully.';
        } else {
            // Error occurred while updating time values
            echo 'Error: ' . mysqli_error($conn);
        }
    } else {
        // Insert a new time entry

        // Insert the time_in value into the time_in table

        $insert_in_sql = "INSERT INTO time_in (name, datetime, location, photo_loc) VALUES ('$name', '$date $time_in', 'None', 'None')";

        $insert_in_result = mysqli_query($conn, $insert_in_sql);

        // Insert the time_out value into the time_out table
        $insert_out_sql = "INSERT INTO time_out (name, datetime) VALUES ('$name', '$date $time_out')";
        $insert_out_result = mysqli_query($conn, $insert_out_sql);

        if ($insert_in_result && $insert_out_result) {
            // Time values inserted successfully
            echo 'Time values inserted successfully.';
        } else {
            // Error occurred while inserting time values
            echo 'Error: ' . mysqli_error($conn);
        }
    }

    // Refresh the page to display the updated/inserted values
    header('Location: manual_inout.php');
    exit();
} else {
    // Invalid form submission
    echo 'Invalid form submission.';
}

?>
