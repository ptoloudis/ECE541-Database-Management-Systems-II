<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isbn = $_POST['isbn'];

    $sql = "SELECT * FROM Book WHERE isbn = '$isbn'";
    $result = $conn->query($sql);

    if ($result->num_rows < 1) {
        header("Location: error.php?message=Book not exist!");
        exit();
    }

    $row = $result->fetch_assoc();
    $id = $row['id'];
    if (empty($_POST['quantity'])) {
        $quantity = $row['quantity'];
    } else {
        $quantity = $_POST['quantity'];
    }
    
    $sql = "UPDATE Book 
        SET quantity = '$quantity' 
        WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result) {
        header("Location: success.php?message=Book changed successfully!");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" type="text/css" href="../style.css"> -->
    <link rel="stylesheet" type="text/css" href="menu_style.css">
    <title>Change Book</title>
    </head>
<body>
    <?php include 'dropdowns.php'; ?>

    <h2>Change a Book</h2>
    <form method="post">
        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" required><br>    

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" min=1><br>

        <input type="submit" value="Change Book">
    </form>
</body>
</html>