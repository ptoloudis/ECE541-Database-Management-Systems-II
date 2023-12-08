<?php
session_start();
include '../db.php';
include 'dropdowns.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $isbn = $_POST['isbn'];
    $genres = $_POST['genres'];
    $publisher = $_POST['publisher'];
    $quantity = $_POST['quantity'];

    $sql = "SELECT * FROM Book WHERE isbn = '$isbn'";
    $result = $conn->query($sql);

    if ($result->num_rows >= 1) {
        header("Location: error.php?message=Book already exists!");
        exit();
    }

    $sql = "INSERT INTO Book (title, author, description, isbn, genres, publisher, quantity) VALUES ('$title', '$author', '$description', '$isbn', '$genres', '$publisher', '$quantity')";

    if ($conn->query($sql) === TRUE) {
        header("Location: success.php?message=Book added successfully!");
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
    <title>Add Book</title>
    </head>
<body>
    <h1>Add a Book</h1>
    <form method="post">
        <label for="title">Title:</label>
        <input type="text" name="title" required><br>

        <label for="author">Author:</label>
        <input type="text" name="author" required><br>

        <label for="description">Description:</label>
        <textarea name="description" rows="4" required></textarea><br>

        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" required><br>

        <label for="genres">Genres:</label>
        <input type="text" name="genres" required><br>

        <label for="publisher">Publisher:</label>
        <input type="text" name="publisher" required><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" min=1  required><br>

        <input type="submit" value="Add Book">
    </form>
</body>
</html>