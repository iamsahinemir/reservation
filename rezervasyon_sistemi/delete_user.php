<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
//eeş
    // Önce kullanıcının rezervasyonlarını silin
    $sql = "DELETE FROM reservations WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Şimdi kullanıcıyı silin
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Kullanıcı başarıyla silindi.";
    } else {
        $_SESSION['status'] = "Kullanıcı silme başarısız.";
    }

    $stmt->close();
    $conn->close();

    header("Location: admin_users.php");
    exit();
}
?>
