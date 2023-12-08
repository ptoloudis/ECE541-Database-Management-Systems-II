<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $select = $_POST['Select'];
    $search = $_POST['search'];

    if ($select == 'Name & Surname') {
        
        if (empty($search)) {
            header("Location: error.php?message=Search field is empty!");
            exit();
        }
        $search = explode(' ', $search);
        $sql = "SELECT * FROM User WHERE name = '$search[0]' AND surname = '$search[1]'";
    } else {
        if (empty($search)) {
            header("Location: error.php?message=Search field is empty!");
            exit();
        }
        $sql = "SELECT * FROM User WHERE $select = '$search'";
    }

    $result = $conn->query($sql);
    if ($result->num_rows < 1) {
        header("Location: error.php?message=User not exist!");
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
    <title>Find User</title>
    </head>
<body>
    <?php include 'dropdowns.php'; ?>
    <h1>Find User</h1>
    <form method="post">
        <label for="Select">Select a User by:</label>
        <select name="Select" id="Select">
            <option value="name">Name</option>
            <option value="surname">Surname</option>
            <option value="email">Email</option>
            <option value="Name & Surname">Name & Surname</option>
        </select>
        <label for="search">Search:</label>
        <input type="text" name="search" id="search">
        <input type="submit" value="Search">

        <table>
            <tr>
                <th>name</th>
                <th>surname</th>
                <th>email</th>
                <th>admin</th>
            </tr>
            <?php 
                if (empty($result)) {
                    $row = array(
                        'name' => '',
                        'surname' => '',
                        'email' => '',
                        'admin' => ''
                    );
                } else
                    while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['surname']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['admin']; ?></td>
                </tr>
            <?php endwhile; ?>
    </form>
</body>
</html>