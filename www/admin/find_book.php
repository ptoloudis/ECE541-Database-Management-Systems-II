<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$result = null; // Initialize $result as null

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $select = $_POST['Select'];
    $search = $_POST['search'];

    if (empty($select)) {
        $sql = "SELECT * FROM Book";
    } else {
        if (empty($search)) {
            header("Location: error.php?message=Search field is empty!");
            exit();
        }
        $sql = "SELECT * FROM Book WHERE $select LIKE '%$search%'";
    }
    $result = $conn->query($sql);

    if ($result === FALSE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        $result = null; // Reset $result to null if query fails
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>Find Books</title>
</head>
<body>
    <div class="container">
        <div class="top-right">
            <a href="index.php" class="btn btn-back">Back</a>
        </div>

        <h2>Find Books</h2>
        <form method="post" class="login-container">
            <div class="form-group">
                <label for="Select">Select a Book by:</label>
                <select name="Select" id="Select" class="form-control">
                    <option value="isbn">ISBN</option>
                    <option value="title">Title</option>
                    <option value="author">Author</option>
                    <option value="publisher">Publisher</option>
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
                    <th>ISBN</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Genres</th>
                    <th>Publisher</th>
                    <th>Quantity</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['isbn']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['genres']); ?></td>
                        <td><?php echo htmlspecialchars($row['publisher']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
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
