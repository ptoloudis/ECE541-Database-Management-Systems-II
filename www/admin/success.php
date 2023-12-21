<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>Success</title>
</head>
<body>
    <div class="container">
        <?php include 'dropdowns.php'; ?>
        <div class="success-message">
            <h2>Success</h2>
            <p><?php echo htmlspecialchars($_GET['message']); ?></p>
        </div>
    </div>
</body>
</html>
