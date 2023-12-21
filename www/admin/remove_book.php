<?php
session_start();
include '../db.php'; // Ensure this path is correct

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $removeType = $_POST['Select'];
    $removeValue = $_POST['Remove'];

    switch ($removeType) {
        case 'isbn':
            $sql = "DELETE FROM Book WHERE isbn = ?";
            break;
        case 'title':
            $sql = "DELETE FROM Book WHERE title = ?";
            break;
        case 'author':
            $sql = "DELETE FROM Book WHERE author = ?";
            break;
        case 'publisher':
            $sql = "DELETE FROM Book WHERE publisher = ?";
            break;
        default:
            $sql = "DELETE FROM Book WHERE isbn = ?";
            break;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $removeValue);
    if ($stmt->execute()) {
        $message = 'Book removed successfully.';
    } else {
        $message = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>Remove Books</title>
</head>
<body>
    <div class="container">
        <div class="top-right">
            <a href="index.php" class="btn btn-back">Back</a>
        </div>

        <h2>Remove Books</h2>
        <form method="post" class="login-container">
            <div class="form-group">
                <label for="Select">Remove Book by:</label>
                <select name="Select" id="Select" class="form-control">
                    <option value="isbn">ISBN</option>
                    <option value="title">Title</option>
                    <option value="author">Author</option>
                    <option value="publisher">Publisher</option>
                </select>
            </div>

            <div class="form-group">
                <label for="Remove">Remove:</label>
                <input type="text" name="Remove" id="Remove" class="form-control">
            </div>

            <input type="submit" value="Remove" class="btn">
        </form>

        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
