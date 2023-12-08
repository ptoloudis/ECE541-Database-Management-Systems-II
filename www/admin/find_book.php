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
    $select = $_POST['Select'];
    $search = $_POST['search'];

    if (empty($select)) {
        $sql = "SELECT * FROM Book";
    } else {
        
        if (empty($search)) {
            header("Location: error.php?message=Search field is empty!");
            exit();
        }
        $sql = "SELECT * FROM Book WHERE $select = '$search'";
    }
    $result = $conn->query($sql);

    if ($result->num_rows < 1) {
        header("Location: error.php?message=Book not exist!");
        exit();
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
    <title>Find Books</title>
    </head>
<body>
    <?php include 'dropdowns.php'; ?>
    <h1>Find Books</h1>
    <form method="post">
        <label for="Select">Select a Book by:</label>
        <select name="Select" id="Select">
            <option value="isbn">ISBN</option>
            <option value="title">Title</option>
            <option value="author">Author</option>
            <option value="publisher">Publisher</option>
        </select>
        <label for="search">Search:</label>
        <input type="text" name="search" id="search">
        <input type="submit" value="Search">

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
            <?php 
                if (empty($result)) {
                    $row = array(
                        'isbn' => '',
                        'title' => '',
                        'author' => '',
                        'description' => '',
                        'genres' => '',
                        'publisher' => '',
                        'quantity' => ''
                    );
                } else
                    while ($row = $result->fetch_assoc()): ?>
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
    </form>
</body>
</html>