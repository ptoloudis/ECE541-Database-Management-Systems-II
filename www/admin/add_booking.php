<?php
session_start();
include '../db.php'; // Ensure this path is correct

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $isbn = $_POST['ISBN'];

    // Check if the book exists and is available
    $stmt = $conn->prepare("SELECT * FROM Book WHERE isbn = ?");
    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $message = 'No book found with the provided ISBN.';
    } else {
        $book = $result->fetch_assoc();
        if ($book['quantity'] <= 0) {
            $message = 'This book is currently not available.';
        } else {
            // Assuming you have a table for users and bookings
            // Check if the email exists in the users table
            $stmt = $conn->prepare("SELECT * FROM User WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $userResult = $stmt->get_result();

            if ($userResult->num_rows == 0) {
                $message = 'No user found with the provided email.';
            } else {
                $user = $userResult->fetch_assoc();
                $userId = $user['id'];

                // Insert the booking
                $stmt = $conn->prepare("INSERT INTO Booking (user_id, book_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $userId, $book['id']);

                if ($stmt->execute()) {
                    $message = 'Booking added successfully!';
                    // Update book quantity
                    $newQuantity = $book['quantity'] - 1;
                    $updateStmt = $conn->prepare("UPDATE Book SET quantity = ? WHERE id = ?");
                    $updateStmt->bind_param("ii", $newQuantity, $book['id']);
                    $updateStmt->execute();
                } else {
                    $message = "Error: " . $stmt->error;
                }
            }
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
    <title>Add Booking</title>
</head>
<body>
    <div class="container">
        <div class="top-right">
            <a href="index.php" class="btn btn-back">Back</a>
        </div>

        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <h2>Add a Booking</h2>
        <form method="post" class="login-container">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="ISBN">ISBN:</label>
                <input type="text" name="ISBN" class="form-control" required>
            </div>

            <input type="submit" value="Add Booking" class="btn">
        </form>
    </div>
</body>
</html>
