<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$result = null; // Initialize $result as null

$sql = "
    SELECT 
        User.Name, 
        User.surname,
        Book.Title,
        Booking.booked_date, 
        Booking.date_returned, 
        Booking.expiration_date
    FROM Booking
    INNER JOIN Book ON Booking.book_id = Book.id 
    INNER JOIN User ON Booking.user_id = User.id
    WHERE Booking.expiration_date < CURDATE() AND Booking.date_returned = '0000-00-00'
";

$result = $conn->query($sql);

if ($result === FALSE) {
    echo "Error: " . $conn->error;
    $result = null; // Reset $result to null if query fails
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>Expired Bookings</title>
</head>
<body>
    <div class="container">
        <div class="top-right">
            <a href="index.php" class="btn btn-back">Back</a>
        </div>

        <h2>Expired Bookings</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Surname</th>
                <th>Title</th>
                <th>Booked Date</th>
                <th>Expected Date</th>
            </tr>
            <?php 
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['surname']); ?></td>
                            <td><?php echo htmlspecialchars($row['Title']); ?></td>
                            <td><?php echo htmlspecialchars($row['booked_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['expiration_date']); ?></td>
                        </tr>
                    <?php endwhile;
                } else {
                    echo '<tr><td colspan="5">No expired bookings.</td></tr>';
                }
            ?>
        </table>
    </div>
</body>
</html>
