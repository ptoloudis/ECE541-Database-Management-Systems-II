<?php
session_start();
include '../db.php'; // Ensure this path is correct

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$result = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchType = $_POST['Select'];
    $searchQuery = $_POST['search'];

    $sql = "SELECT User.name, User.surname, Book.title, Booking.booked_date, Booking.date_returned, Booking.expiration_date FROM Booking JOIN User ON Booking.user_id = User.id JOIN Book ON Booking.book_id = Book.id ";

    switch ($searchType) {
        case 'isbn':
            $sql .= "WHERE Book.isbn LIKE ?";
            break;
        case 'email':
            $sql .= "WHERE User.email LIKE ?";
            break;
        case 'Booked date':
            $sql .= "WHERE Booking.booked_date LIKE ?";
            break;
        case 'Returned date':
            $sql .= "WHERE Booking.date_returned LIKE ?";
            break;
        case 'Expected date':
            $sql .= "WHERE Booking.expiration_date LIKE ?";
            break;
        default:
            $sql .= "WHERE Book.isbn LIKE ?";
            break;
    }

    $stmt = $conn->prepare($sql);
    $likeQuery = "%$searchQuery%";
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>Find Bookings</title>
</head>
<body>
    <div class="container">
        <div class="top-right">
            <a href="index.php" class="btn btn-back">Back</a>
        </div>

        <h2>Find Bookings</h2>
        <form method="post" class="login-container">
            <div class="form-group">
                <label for="Select">Select a Booking by:</label>
                <select name="Select" id="Select" class="form-control">
                    <option value="isbn">ISBN</option>
                    <option value="email">Email</option>
                    <option value="Booked date">Booked date</option>
                    <option value="Returned date">Returned date</option>
                    <option value="Expected date">Expected date</option>
                </select>
            </div>

            <div class="form-group">
                <label for="search">Search:</label>
                <input type="text" name="search" id="search" class="form-control">
            </div>

            <input type="submit" value="Search" class="btn">
        </form>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Title</th>
                    <th>Booked Date</th>
                    <th>Returned Date</th>
                    <th>Expected Date</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['surname']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['booked_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['date_returned']); ?></td>
                        <td><?php echo htmlspecialchars($row['expiration_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <p>No results found.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
