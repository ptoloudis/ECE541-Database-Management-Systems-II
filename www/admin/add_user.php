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
    <div class="dropdown">
        <button class="dropbtn">Books</button>
        <div class="dropdown-content">
            <a href="add_book.php">Add</a>
            <a href="change_book.php">Change</a>
            <a href="find_book.php">Find</a>
        </div>        
    </div>
    <div class="dropdown">
        <button class="dropbtn">Users</button>
        <div class="dropdown-content">
            <a href="add_user.php">Add</a>
            <a href="change_user.php">Change</a>
            <a href="find_user.php">Find</a>
        </div>
    </div>
    <div class="dropdown">
        <button class="dropbtn">Booking</button>
        <div class="dropdown-content">
            <a href="add_booking.php">Add</a>
            <a href="change_booking.php">Change</a>
            <a href="find_booking.php">Find</a>
            <a href="expirent_booking.php">Expirent</a>
            <a href="return_booking.php">Return</a>
        </div>
    </div>

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
        <input type="submit" value="Add">
    </form>
</body>
</html>