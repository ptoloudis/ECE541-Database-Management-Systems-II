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
    if (empty($_POST['title'])) {
        $title = $row['title'];
    } else {
        $title = $_POST['title'];
    }
    if (empty($_POST['author'])) {
        $author = $row['author'];
    } else {
        $author = $_POST['author'];
    }
    if (empty($_POST['description'])) {
        $description = $row['description'];
    } else {
        $description = $_POST['description'];
    }
    if (empty($_POST['genres'])) {
        $genres = $row['genres'];
    } else {
        $genres = $_POST['genres'];
    }
    if (empty($_POST['publisher'])) {
        $publisher = $row['publisher'];
    } else {
        $publisher = $_POST['publisher'];
    }
    if (empty($_POST['quantity'])) {
        $quantity = $row['quantity'];
    } else {
        $quantity = $_POST['quantity'];
    }
    
    $sql = "UPDATE Book SET title = '$title', author = '$author', description = '$description', genres = '$genres', publisher = '$publisher', quantity = '$quantity' WHERE id = '$id'";
    $result = $conn->query($sql);
    if ($result) {
        header("Location: success.php?message=Book changed successfully!");
        exit();
    } else {
        header("Location: error.php?message=Error while changing book!");
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
        <label for="title">Title:</label>
        <input type="text" name="title"><br>

        <label for="author">Author:</label>
        <input type="text" name="author"><br>

        <label for="description">Description:</label>
        <textarea name="description" rows="4"></textarea><br>

        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" required><br>

        <label for="genres">Genres:</label>
        <input type="text" name="genres"><br>

        <label for="publisher">Publisher:</label>
        <input type="text" name="publisher"><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" min=1><br>

        <input type="submit" value="Add Book">
    </form>
</body>
</html>