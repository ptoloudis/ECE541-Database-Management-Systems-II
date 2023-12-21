<?php
session_start();
include '../db.php'; // Ensure this path is correct

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentEmail = $_POST['email'];


    // Check if the user exists
    $stmt = $conn->prepare("SELECT * FROM User WHERE email = ?");
    $stmt->bind_param("s", $currentEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $message = 'No user found with the provided email.';
    } else {
        // Update the user's details
        $row = $result->fetch_assoc();
        if(empty($_POST['Name'])){
            $newName = $row['name'];
        } else {
            $newName = $_POST['Name'];
        }
        if (empty($_POST['surname'])){
            $newSurname = $row['surname'];
        } else {
            $newSurname = $_POST['surname'];
        }
        if (empty($_POST['email2'])){
            $newEmail = $row['email'];
        } else {
            $newEmail = $_POST['email2'];
        }
        if (empty($_POST['admin']) ){
            $admin = $row['admin'];
        } else {
            if ($_POST['admin'] == 1){
                $admin = 1;
            } else {
                $admin = 0;
            }
        }


        $sql = "UPDATE User SET name = ?, surname = ?, email = ?, admin = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssds", $newName, $newSurname, $newEmail, $admin, $currentEmail);

        if ($stmt->execute()) {
            $message = 'User updated successfully!';
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
    <title>Change User</title>
</head>
<body>
    <div class="container">
        <div class="top-right">
            <a href="index.php" class="btn btn-back">Back</a>
        </div>

        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <h2>Change a User</h2>
        <form method="post" class="login-container">
            <div class="form-group">
                <label for="email">Current Email:</label>
                <input type="text" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="Name">New Name (optional):</label>
                <input type="text" name="Name" class="form-control">
            </div>

            <div class="form-group">
                <label for="surname">New Surname (optional):</label>
                <input type="text" name="surname" class="form-control">
            </div>

            <div class="form-group">
                <label for="email2">New Email (optional):</label>
                <input type="text" name="email2" class="form-control">
            </div>

            <div class="form-group">
                <label for="admin">Admin (-1 or 1):</label>
                <input type="number" name="admin" class="form-control" min="-1" max="1">
            </div>

            <input type="submit" value="Change User" class="btn">
        </form>
    </div>
</body>
</html>
