<?php
// User-only access verification
session_start();
if ($_SESSION['admin'] != '0' && $_SESSION['admin'] != '1') {
    header("Location: ../index.php");
    exit();
}

// User logic for viewing available books goes here
echo "User Book Viewing Page";
// Include logic to display available books
?>
