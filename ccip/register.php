<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rezervasyon_sistemi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    function properEmail($email) {
        $mail_regex = "/khas.edu.tr/";
        return preg_match($mail_regex, $email);
    }

    function properPassword($password) {
        $password_regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        return preg_match($password_regex, $password);
    }

    if (properEmail($email)) {
        if (properPassword($password)) {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password_hash);

            if ($stmt->execute()) {
                header("Location: index.html");
                exit();
            } else {
                echo "Hata: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Şifreniz en az bir adet büyük karakter, en az bir adet küçük karakter, en az bir adet özel karakter içermeli ve şifreniz en az 8 karakter olmalıdır.";
        }
    } else {
        echo "Mail adresiniz 'khas.edu.tr' içermelidir.";
    }

    $conn->close();
}
?>
