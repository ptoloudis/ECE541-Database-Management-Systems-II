<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Create empty array
$result = array(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $isbn = $_POST['isbn'];

    $sql = "Select id FROM User WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result === false) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    if ($result->num_rows < 1) {
        header("Location: error.php?message=Booking not exist!");
        exit();
    }
    
    $row = $result->fetch_assoc();
    $user_id = $row['id'];

    $sql = "Select id, quantity FROM Book WHERE isbn = '$isbn'";
    $result = $conn->query($sql);
    if ($result === false) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    if ($result->num_rows < 1) {
        header("Location: error.php?message=Booking not exist!");
        exit();
    }

    $row = $result->fetch_assoc();
    $book_id = $row['id'];
    $quantity =  $row['quantity'] + 1;

    $sql = "UPDATE Booking SET date_returned = NOW() WHERE user_id = '$user_id' AND book_id = '$book_id'";
    $result = $conn->query($sql);
    if ($result === false) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $sql = "UPDATE Book SET quantity = '$quantity' WHERE id = '$book_id'";
    if ($result) {
        header("Location: success.php?message=Book returned successfully!");
        exit();
    }    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="menu_style.css">
    <title>Return Book</title>
    </head>
<body>
    <?php include 'dropdowns.php'; ?>
    <h1>Return Book</h1>
    <form method="post">
        <label> Email: </label>
        <input type="text" name="email" placeholder="Enter email" require>
        <label> ISBN: </label>
        <input type="text" name="isbn" placeholder="Enter ISBN" require>
        <input type="submit" value="Return">        
    </form>
</body>
</html>