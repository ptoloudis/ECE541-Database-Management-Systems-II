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
    <div class="form-container">
        <h1>Hello <?php echo $userName; ?></h1>

        <h2>Books</h2>
        <button onclick="window.location.href='add_book.php'">Add</button>
        <button onclick="window.location.href='change_book.php'">Change</button>
        <button onclick="window.location.href='find_book.php'">Find</button>

        <h2>Users</h2>
        <button onclick="window.location.href='add_user.php'">Add</button>
        <button onclick="window.location.href=''">Change</button>
        <button onclick="window.location.href='users.php'">Find</button>

        <h2>Booking</h2>
        <button onclick="window.location.href='add_booking.php'">Add</button>
        <button onclick="window.location.href=''">Change</button>
        <button onclick="window.location.href=''">Find</button>
        <button onclick="window.location.href=''">Expirent</button>
        <button onclick="window.location.href=''">Return</button>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
