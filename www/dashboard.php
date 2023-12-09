<?php
session_start();
include 'db.php';

// function custom_log($message) {
//     $logFile = './my_log.log'; 
//     $timestamp = date('Y-m-d H:i:s');
//     $logMessage = $timestamp . ' - ' . $message . PHP_EOL;
//     file_put_contents($logFile, $logMessage, FILE_APPEND);
// }

if (!isset($_SESSION['user_id'])) {
    #custom_log("oo " . $_SESSION['user_id']);
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
