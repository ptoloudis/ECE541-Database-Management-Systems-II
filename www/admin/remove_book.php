<?php
session_start();
include '../db.php';
// include 'dropdowns.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Create empty array
$result = array(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $select = $_POST['Select'];
    $Remove = $_POST['Remove'];

    
        
    if (empty($Remove) || empty($select)) {
        header("Location: error.php?message=Remove field is empty!");
        exit();
    }
    
    $sql = "DELETE FROM Book WHERE $select = '$Remove'";

    if ($conn->query($sql) === TRUE) {
        header("Location: success.php?message=Book remove successfully!");
    } else {
        header("Location: error.php?message=Book not exist!");
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
    <title>Remove Books</title>
    </head>
<body>
    <?php include 'dropdowns.php'; ?>
    <h1>Remove Books</h1>
    <form method="post">
        <label for="Select">Remove Book by:</label>
        <select name="Select" id="Select">
            <option value="isbn">ISBN</option>
            <option value="title">Title</option>
            <option value="author">Author</option>
            <option value="publisher">Publisher</option>
        </select>
        <label for="Remove">Remove:</label>
        <input type="text" name="Remove" id="Remove">
        <input type="submit" value="Remove">
    </form>
</body>
</html>