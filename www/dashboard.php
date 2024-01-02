<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Redirect based on user type
if ($_SESSION['admin'] == '1') {
    header("Location: admin/index.php");
    exit();
} else {
    header("Location: user/books.php");
    exit();
}
?>
