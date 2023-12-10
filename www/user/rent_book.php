<?php
session_start();
include '../db.php'; // Adjust the path as needed

header('Content-Type: application/json'); // Specify the content type as JSON

if ($_SESSION['admin'] != '0' && $_SESSION['admin'] != '1') {
    echo json_encode(["status" => "error", "message" => "Access denied."]);
    exit();
}

if (!isset($_GET['book_id'])) {
    echo json_encode(["status" => "error", "message" => "No book specified."]);
    exit();
}

$bookId = $_GET['book_id'];
$userId = $_SESSION['user_id']; // Ensure this is set during login

// Check if the user is already renting the book
$sqlCheck = "SELECT * FROM Booking WHERE user_id = $userId AND book_id = $bookId AND date_returned IS NULL";
$resultCheck = $conn->query($sqlCheck);
if ($resultCheck->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "You are already renting this book."]);
    exit();
}

// Check if the book is available (quantity > 0)
$sqlAvailable = "SELECT quantity FROM Book WHERE id = $bookId";
$resultAvailable = $conn->query($sqlAvailable);
if ($resultAvailable->num_rows > 0) {
    $row = $resultAvailable->fetch_assoc();
    if ($row['quantity'] <= 0) {
        echo json_encode(["status" => "error", "message" => "This book is currently not available."]);
        exit();
    }
}

// Update the quantity of the book
$sqlUpdateQuantity = "UPDATE Book SET quantity = quantity - 1 WHERE id = $bookId";
$conn->query($sqlUpdateQuantity);

// Insert the new booking
$today = date("Y-m-d");
$expirationDate = date("Y-m-d", strtotime("+1 week")); // Example: 1 week rental
$sqlRent = "INSERT INTO Booking (user_id, book_id, booked_date, expiration_date) VALUES ($userId, $bookId, '$today', '$expirationDate')";

// After updating the quantity in the database
if ($conn->query($sqlUpdateQuantity) === TRUE) {
    // Rest of your code for inserting the booking
    if ($conn->query($sqlRent) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Book rented successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error renting book: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Error updating book quantity"]);
}


$conn->close();
?>
