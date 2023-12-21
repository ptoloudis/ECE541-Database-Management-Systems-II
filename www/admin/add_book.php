<?php
session_start();
include '../db.php'; // Ensure this path is correct
// include 'dropdowns.php'; // Ensure this path is correct

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $isbn = $_POST['isbn'];
    $genres = $_POST['genres'];
    $publisher = $_POST['publisher'];
    $quantity = $_POST['quantity'];

    // Prepared statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT * FROM Book WHERE isbn = ?");
    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows >= 1) {
        $message = 'Book already exists!';
    } else {
        $stmt = $conn->prepare("INSERT INTO Book (title, author, description, isbn, genres, publisher, quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $title, $author, $description, $isbn, $genres, $publisher, $quantity);

        if ($stmt->execute()) {
            $message = 'Book added successfully!';
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>Add Book</title>
</head>
<body>
    <div class="container">
        <div class="top-left">
            <a href="index.php" class="btn btn-back">Back</a>
        </div>

        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <h2>Add a Book</h2>
        <form method="post" class="login-container">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" name="author" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text" name="isbn" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="genres">Genres:</label>
                <input type="text" name="genres" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="publisher">Publisher:</label>
                <input type="text" name="publisher" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" class="form-control" min="1" required>
            </div>

            <input type="submit" value="Add Book" class="btn">
        </form>
    </div>
</body>
</html>
