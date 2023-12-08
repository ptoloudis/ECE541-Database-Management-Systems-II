<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Create empty array
$result = array(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
    <title>Expected Books</title>
    </head>
<body>
    <?php include 'dropdowns.php'; ?>
    <h1>Expected Books</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Surname</th>
            <th>Title</th>
            <th>Booked date</th>
            <th>Returned date</th>
            <th>Expected date</th>
        </tr>
        <?php 
            if (empty($result)) {
                $row = array(
                    'name' => '',
                    'surname' => '',
                    'Title' => '',
                    'booked_date' => '',
                    'date_returned' => '',
                    'expiration_date' => ''
                );
            } else
                while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['Name']; ?></td>
                <td><?php echo $row['surname']; ?></td>
                <td><?php echo $row['Title']; ?></td>
                <td><?php echo $row['booked_date']; ?></td>
                <td><?php echo $row['date_returned']; ?></td>
                <td><?php echo $row['expiration_date']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>