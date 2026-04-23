<?php

// Define the allowed roles for each page
$allowedRoles = array(
    'admin' => array(
        'admin_dash.php',
        'departments.php',
        'attendances.php',
        'accounts.php',
        'users.php',
        'leaves.php'
    ),
    'regular' => array(
        'reg_dash.php',
        'reg_attendance.php',
        'reg_leave_ot.php'
    )
);

// Get the current page name
$currentPage = basename($_SERVER['PHP_SELF']);

// Check if the user's role is allowed to access the current page
if (isset($_SESSION['role']) && isset($allowedRoles[$_SESSION['role']])) {
    $allowedPages = $allowedRoles[$_SESSION['role']];
    if (!in_array($currentPage, $allowedPages)) {
        // Redirect back to index.php or any other appropriate page
        header('Location: index.php');
        exit();
    }
} else {
    // Redirect back to index.php or any other appropriate page
    header('Location: index.php');
    exit();
}
?>
