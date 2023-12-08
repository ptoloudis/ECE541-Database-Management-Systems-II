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

    
        
    if ($select == 'Name & Surname') {
        
        if (empty($delete)) {
            header("Location: error.php?message=delete field is empty!");
            exit();
        }
        $delete = explode(' ', $delete);
        $sql = "DELETE FROM User WHERE name = '$delete[0]' AND surname = '$delete[1]'";
    } else {
        if (empty($delete)) {
            header("Location: error.php?message=delete field is empty!");
            exit();
        }
        $sql = "DELETE FROM User WHERE $select = '$delete'";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: success.php?message=User remove successfully!");
    } else {
        header("Location: error.php?message=User not exist!");
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
    <title>Remove User</title>
    </head>
<body>
    <?php include 'dropdowns.php'; ?>
    <h1>Remove User</h1>
    <form method="post">
        <label for="Select">Select a User by:</label>
        <select name="Select" id="Select">
            <option value="email">Email</option>
            <option value="Name & Surname">Name & Surname</option>
            <option value="name">Name</option>
            <option value="surname">Surname</option>
        </select>
        <label for="delete">Delete:</label>
        <input type="text" name="delete" id="delete">
        <input type="submit" value="delete">
    </form>
</body>
</html>