<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $ISBN = $_POST['ISBN'];

    $sql = "SELECT * FROM User WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows < 1) {
        header("Location: error.php?message=User does not exist!");
        exit();
    }

    $sql = "SELECT * FROM Book WHERE ISBN = '$ISBN'";
    $result_2 = $conn->query($sql);
    if ($result_2->num_rows < 1) {
        header("Location: error.php?message=Book does not exist!");
        exit();
    }

    $user_id = $result->fetch_assoc()['id'];
    $book_id = $result_2->fetch_assoc()['id'];
    $booked_date = date("Y-m-d");
    $expire_date = date("Y-m-d", strtotime("+1 month"));
    $return_date = date("0000-00-00");

    $sql = "INSERT INTO Booking (user_id, book_id, booked_date, expiration_date, date_returned) VALUES ('$user_id', '$book_id', '$booked_date', '$expire_date', '$return_date')";
    if ($conn->query($sql) === TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
    <title>Add Booking</title>
    </head>
<body>
    <?php include 'dropdowns.php'; ?>

    <h2>Add a Booking</h2>
    <form method="post">
        <label for="email">Email</label>
        <input type="text" name="email" required><br>
        <label for="name">ISBN</label>
        <input type="text" name="ISBN" required><br>
        <input type="submit" value="Add">
    </form>
</body>
</html>