<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

function getUserName($userId) {
    global $conn;
    $sql = "SELECT * FROM User WHERE id = $userId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return ($row['name'] . " " . $row['surname']);
    } else {
        return "Unknown";
    }
}

// Get the user's name
$userName = getUserName($_SESSION['user_id'])
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>Admin Home</title>
</head>
<body>
    <div class="admin-container">
        <h1 class="admin-heading">Hello, <?php echo $userName; ?></h1>

        <div class="admin-section">
            <h2>Books</h2>
            <div class="button-group">
                <button onclick="window.location.href='add_book.php'">Add</button>
                <button onclick="window.location.href='change_book.php'">Change</button>
                <button onclick="window.location.href='find_book.php'">Find</button>
                <button onclick="window.location.href='remove_book.php'">Remove</button>
            </div>
        </div>

        <div class="admin-section">
            <h2>Users</h2>
            <div class="button-group">
                <button onclick="window.location.href='add_user.php'">Add</button>
                <button onclick="window.location.href='change_user.php'">Change</button>
                <button onclick="window.location.href='find_user.php'">Find</button>
                <button onclick="window.location.href='remove_user.php'">Remove</button>
            </div>
        </div>

        <div class="admin-section">
            <h2>Booking</h2>
            <div class="button-group">
                <button onclick="window.location.href='add_booking.php'">Add</button>
                <button onclick="window.location.href='make_return.php'">Make return</button>
                <button onclick="window.location.href='find_booking.php'">Find</button>
                <button onclick="window.location.href='expirent_booking.php'">Expirent</button>
                <button onclick="window.location.href='return_booking.php'">Not Return</button>
            </div>
        </div>
    </div>
</body>
</html>
