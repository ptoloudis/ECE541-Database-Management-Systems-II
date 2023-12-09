<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $sql = "SELECT * FROM User WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows < 1) {
        header("Location: error.php?message=User not exist!");
        exit();
    }
    
    $row = $result->fetch_assoc();
    $id = $row['id'];
    if (empty($_POST['Name'])) {
        $name = $row['name'];
    } else {
        $name = $_POST['Name'];
    }
    if (empty($_POST['surname'])) {
        $surname = $row['surname'];
    } else {
        $surname = $_POST['surname'];
    }
    if (empty($_POST['email2'])) {
        $email = $row['email'];
    } else {
        $email = $_POST['email2'];
    }
    if (empty($_POST['admin_field'])) {
        echo "empty";
        $admin = $row['admin'];
    } else {
        $admin = $_POST['admin'];
    }

    $sql = "UPDATE User 
        SET name = '$name', 
        surname = '$surname', 
        email = '$email', 
        admin = '$admin' 
        WHERE id = '$id'";
    $result = $conn->query($sql);
    if ($result) {
        header("Location: success.php?message=User changed successfully!");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
    <title>Change User</title>
    </head>
<body>
    <?php include 'dropdowns.php'; ?>

    <h2>Change a User</h2>
    <form method="post">
        <label for="email">Email:</label>
        <input type="text" name="email" required><br>

        <label for="Name">Name:</label>
        <input type="text" name="Name"><br>

        <label for="surname">Surname:</label>
        <input type="text" name="surname"><br>

        <label for="email2">Email:</label>
        <input type="text" name="email2"><br>

        <label for="admin">Admin</label>
        <input type="number" name="admin" min=0 max=1>
        <input type="checkbox" name="admin_field" value="1"><br>
        <input type="submit" value="Change User">
    </form>
</body>
</html>