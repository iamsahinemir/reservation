<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];

    // First, delete the user's reservations
    $sql = "DELETE FROM reservations WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Now, delete the user
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $_SESSION['status'] = "User successfully deleted.";
    } else {
        $_SESSION['status'] = "Failed to delete user.";
    }

    $stmt->close();
    $conn->close();

    header("Location: admin_users.php");
    exit();
}//eeş
?>
