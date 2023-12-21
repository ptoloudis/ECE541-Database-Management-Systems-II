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
    $isbn = $_POST['isbn'];

    // Check if the user exists
    $stmt = $conn->prepare("SELECT id FROM User WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows == 0) {
        $message = 'No user found with the provided email.';
    } else {
        $user = $userResult->fetch_assoc();
        $userId = $user['id'];

        // Check if the book exists
        $stmt = $conn->prepare("SELECT id FROM Book WHERE isbn = ?");
        $stmt->bind_param("s", $isbn);
        $stmt->execute();
        $bookResult = $stmt->get_result();

        if ($bookResult->num_rows == 0) {
            $message = 'No book found with the provided ISBN.';
        } else {
            $book = $bookResult->fetch_assoc();
            $bookId = $book['id'];

            // Check if there is an active booking for this user and book
            $stmt = $conn->prepare("SELECT * FROM Booking WHERE user_id = ? AND book_id = ? AND date_returned IS NULL");
            $stmt->bind_param("ii", $userId, $bookId);
            $stmt->execute();
            $bookingResult = $stmt->get_result();

            if ($bookingResult->num_rows == 0) {
                $message = 'No active booking found for this user and book.';
            } else {
                // Update the booking to mark the book as returned
                $stmt = $conn->prepare("UPDATE Booking SET date_returned = NOW() WHERE user_id = ? AND book_id = ? AND date_returned IS NULL");
                $stmt->bind_param("ii", $userId, $bookId);

                if ($stmt->execute()) {
                    $message = 'Book returned successfully.';
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
    <title>Return Book</title>
</head>
<body>
    <div class="container">
        <div class="top-right">
            <a href="index.php" class="btn btn-back">Back</a>
        </div>

        <h2>Return Book</h2>
        <form method="post" class="login-container">
            <div class="form-group">
                <label>Email:</label>
                <input type="text" name="email" placeholder="Enter email" required class="form-control">
            </div>

            <div class="form-group">
                <label>ISBN:</label>
                <input type="text" name="isbn" placeholder="Enter ISBN" required class="form-control">
            </div>

            <input type="submit" value="Return" class="btn">
        </form>

        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
