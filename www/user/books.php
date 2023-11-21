<?php
// User-only access verification
session_start();
if ($_SESSION['user_type'] != 'user') {
    header("Location: ../index.php");
    exit();
}

// User logic for viewing available books goes here
echo "User Book Viewing Page";
// Include logic to display available books
?>
