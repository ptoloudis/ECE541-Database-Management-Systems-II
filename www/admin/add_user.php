<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(empty($_POST['admin']))
        $admin = 0;
    else
        $admin = 1;

    $sql = "SELECT * FROM User WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        header("Location: error.php?message=User already exists!");
        exit();
    }

    $sql = "Insert INTO User (name, surname, email, password, admin) VALUES ('$name', '$surname', '$email', '$password', '$admin')";

    if ($conn->query($sql) === TRUE) {
        header("Location: success.php?message=User added successfully!");
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
    <title>Add User</title>
    </head>
<body>
    <?php include 'dropdowns.php'; ?>

    <h2>Add a User</h2>
    <form method="post">
        <label for="name">Name</label>
        <input type="text" name="name" required><br>
        <label for="surname">Surname</label>
        <input type="text" name="surname" required><br>
        <label for="email">Email</label>
        <input type="text" name="email" required><br>
        <label for="password">Password</label>
        <input type="password" name="password" required><br>
        <label for="admin">Admin</label>
        <input type="checkbox" name="admin"><br>
        <input type="submit" value="Add User">
    </form>
</body>
</html>