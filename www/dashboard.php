<?php
// dashboard.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['user_type'] == 'admin') {
    header("Location: admin/users.php");
} else {
    header("Location: user/books.php");
}
?>
