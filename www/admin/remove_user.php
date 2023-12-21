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
        case 'email':
            $sql = "DELETE FROM User WHERE email = ?";
            break;
        case 'Name & Surname':
            list($name, $surname) = explode(' ', $removeValue, 2);
            $sql = "DELETE FROM User WHERE name = ? AND surname = ?";
            break;
        case 'name':
            $sql = "DELETE FROM User WHERE name = ?";
            break;
        case 'surname':
            $sql = "DELETE FROM User WHERE surname = ?";
            break;
        default:
            $sql = "DELETE FROM User WHERE email = ?";
            break;
    }

    $stmt = $conn->prepare($sql);
    if ($removeType == 'Name & Surname') {
        $stmt->bind_param("ss", $name, $surname);
    } else {
        $stmt->bind_param("s", $removeValue);
    }

    if ($stmt->execute()) {
        $message = 'User removed successfully.';
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
    <title>Remove User</title>
</head>
<body>
    <div class="container">
        <div class="top-right">
            <a href="index.php" class="btn btn-back">Back</a>
        </div>

        <h2>Remove User</h2>
        <form method="post" class="login-container">
            <div class="form-group">
                <label for="Select">Select a User by:</label>
                <select name="Select" id="Select" class="form-control">
                    <option value="email">Email</option>
                    <option value="Name & Surname">Name & Surname</option>
                    <option value="name">Name</option>
                    <option value="surname">Surname</option>
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
