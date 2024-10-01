<?php

session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kullanıcıyı ve verify_status durumunu kontrol edelim
    $sql = "SELECT id, name, email, password, role, verify_status FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
//eeş
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // verify_status kontrolü
        if ($user['verify_status'] == 1) {
            // Eğer verify_status 1 ise şifreyi kontrol edelim
            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id']; // Kullanıcı ID'sini oturuma kaydet
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
                $_SESSION['status'] = "Yanlış şifre.";
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['status'] = "Hesabınız doğrulanmamış.";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['status'] = "Bu email ile kayıtlı kullanıcı bulunamadı.";
        header("Location: index.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
