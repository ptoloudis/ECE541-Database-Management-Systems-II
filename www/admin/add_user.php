<?php
session_start();
include '../db.php'; // Ensure this path is correct

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Consider hashing the password
    $admin = isset($_POST['admin']) ? 1 : 0; // Checkbox for admin

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM User WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = 'A user with this email already exists!';
    } else {
        // Insert the new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hashing the password
        $stmt = $conn->prepare("INSERT INTO User (name, surname, email, password, admin) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $name, $surname, $email, $hashedPassword,$admin);

        if ($stmt->execute()) {
            $message = 'User added successfully!';
        } else {
            $message = "Error: " . $stmt->error;
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
    <title>Add User</title>
</head>
<body>
    <div class="container">
        <div class="top-right">
            <a href="index.php" class="btn btn-back">Back</a>
        </div>

        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <h2>Add a User</h2>
        <form method="post" class="login-container">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="surname">Surname:</label>
                <input type="text" name="surname" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="admin">Admin:</label>
                <input type="checkbox" name="admin" class="form-control">
            </div>

            <input type="submit" value="Add User" class="btn">
        </form>
    </div>
</body>
</html>
