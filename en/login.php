<?php

session_start();//eeş
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check the user and verify_status
    $sql = "SELECT id, name, email, password, role, verify_status FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check verify_status
        if ($user['verify_status'] == 1) {
            // If verify_status is 1, check the password
            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id']; // Save the user ID to the session
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] == 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: user.php");
                }
                exit();
            } else {
                $_SESSION['status'] = "Incorrect password.";
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['status'] = "Your account has not been verified.";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['status'] = "No user found with this email.";
        header("Location: index.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
