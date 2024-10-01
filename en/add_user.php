<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['s_number'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (id, name, email, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $number, $name, $email, $password_hash, $role);

    if ($stmt->execute()) {
        $_SESSION['status'] = "User added successfully.";
    } else {
        $_SESSION['status'] = "Failed to add user.";
    }

    $stmt->close();
    $conn->close();

    header("Location: admin_users.php");
    exit();
}
//eeş
?>
