<?php
/*
Bu kodda databaseye kullanıcı ekleniyor.
*/
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { // kullanıcının id numarası veya rolü admin değilse sisteme giriş yasaklanıyor
    header("Location: index.php");
    exit();
}

//eeş
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['s_number'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); // şifre hashleme
    $role = $_POST['role'];

    $sql = "INSERT INTO users (id, name, email, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $number, $name, $email, $password_hash, $role);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Kullanıcı başarıyla eklendi.";
    } else {
        $_SESSION['status'] = "Kullanıcı ekleme başarısız.";
    }

    $stmt->close();
    $conn->close();

    header("Location: admin_users.php");
    exit();
}
?>
