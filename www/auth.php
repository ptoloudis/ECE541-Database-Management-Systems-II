<?php

function login($email, $password, $conn) {
    $query = "SELECT * FROM User WHERE email = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['admin'] = $user['admin']; 
                return true;
            }
        }
        return false;
    } else {
        error_log("SQL preparation failed: " . $conn->error);
        return false;
    }
}
?>
