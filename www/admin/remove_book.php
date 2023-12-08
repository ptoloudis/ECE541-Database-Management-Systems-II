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
    $delete = $_POST['delete'];

    
        
    if (empty($delete) || empty($select)) {
        header("Location: error.php?message=delete field is empty!");
        exit();
    }
    
    // echo "Select: $select, Delete: $delete";
    $sql = "DELETE FROM Book WHERE $select = '$delete'";

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
        <label for="Select">Delete Book by:</label>
        <select name="Select" id="Select">
            <option value="isbn">ISBN</option>
            <option value="title">Title</option>
            <option value="author">Author</option>
            <option value="publisher">Publisher</option>
        </select>
        <label for="delete">Delete:</label>
        <input type="text" name="delete" id="delete">
        <input type="submit" value="delete">
    </form>
</body>
</html>