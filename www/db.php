<?php
$host = 'db';  // Adjust as needed
$user = 'user';  // Your database username
$password = 'password';  // Your database password
$dbname = 'library';  // Your database name

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function closeDb($conn) {
    $conn->close();
}
?>
