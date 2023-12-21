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

    switch ($searchType) {
        case 'name':
            $sql = "SELECT * FROM User WHERE name LIKE ?";
            break;
        case 'surname':
            $sql = "SELECT * FROM User WHERE surname LIKE ?";
            break;
        case 'email':
            $sql = "SELECT * FROM User WHERE email LIKE ?";
            break;
        case 'Name & Surname':
            $sql = "SELECT * FROM User WHERE CONCAT(name, ' ', surname) LIKE ?";
            break;
        default:
            $sql = "SELECT * FROM User WHERE name LIKE ?";
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
    <title>Find User</title>
</head>
<body>
    <div class="container">
        <div class="top-right">
            <a href="index.php" class="btn btn-back">Back</a>
        </div>

        <h2>Find User</h2>
        <form method="post" class="login-container">
            <div class="form-group">
                <label for="Select">Select a User by:</label>
                <select name="Select" id="Select" class="form-control">
                    <option value="name">Name</option>
                    <option value="surname">Surname</option>
                    <option value="email">Email</option>
                    <option value="Name & Surname">Name & Surname</option>
                </select>
            </div>

            <div class="form-group">
                <label for="search">Search:</label>
                <input type="text" name="search" id="search" class="form-control">
            </div>

            <input type="submit" value="Search" class="btn">
        </form>

        <?php if (!empty($result) && $result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th>Admin</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['surname']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo $row['admin'] ? 'Yes' : 'No'; ?></td>
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
