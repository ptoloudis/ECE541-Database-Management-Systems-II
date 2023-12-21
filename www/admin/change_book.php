<?php
session_start();
include '../db.php'; // Ensure this path is correct

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isbn = $_POST['isbn'];
    $quantity = $_POST['quantity'];

    // Check if the book exists
    $stmt = $conn->prepare("SELECT * FROM Book WHERE isbn = ?");
    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $message = 'No book found with the provided ISBN.';
    } else {
        // Update the book's quantity
        $stmt = $conn->prepare("UPDATE Book SET quantity = ? WHERE isbn = ?");
        $stmt->bind_param("is", $quantity, $isbn);

        if ($stmt->execute()) {
            $message = 'Book updated successfully!';
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
    <title>Change Book</title>
</head>
<body>
    <div class="container">
        <div class="top-right">
            <a href="index.php" class="btn btn-back">Back</a>
        </div>

        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <h2>Change a Book</h2>
        <form method="post" class="login-container">
            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text" name="isbn" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" class="form-control" min="1">
            </div>

            <input type="submit" value="Change Book" class="btn">
        </form>
    </div>
</body>
</html>
