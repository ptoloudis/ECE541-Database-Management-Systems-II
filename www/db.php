<?php
$host = 'db';  
$user = 'user';
$password = 'password';
$dbname = 'library';  

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function closeDb($conn) {
    $conn->close();
}
?>
