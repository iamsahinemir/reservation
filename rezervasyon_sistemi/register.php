<?php
session_start();
include('config.php');
//eeş
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'YOUR_PATH\PHPMailer\src\Exception.php';
require 'YOUR_PATH\PHPMailer\src\PHPMailer.php';
require 'YOUR_PATH\PHPMailer\src\SMTP.php';

function sendemail_verify($name, $email, $verify_token) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp-mail.outlook.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'YOUR_MAIL';//Kendi mailinizi buraya ekleyin
        $mail->Password   = 'YOUR_PASSWORD'; // Kendi şifrenizi buraya ekleyin
        $mail->SMTPSecure = 'STARTTLS';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('YOUR_MAIL', 'Laboratuvar Rezervasyon Sistemi');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'E-posta Doğrulama';
        $email_template = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.5;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9;'>
                <h2 style='text-align: center; color: #4B79A1;'>Kayıt İşlemi</h2>
                <p>Merhaba, $name!</p>
                <p>CCIP Laboratuvar Rezervasyon Sistemi'ne kaydolduğunuz için teşekkür ederiz. Lütfen e-posta adresinizi doğrulamak için aşağıdaki bağlantıya tıklayın:</p>
                <p style='text-align: center;'>
                    <a href='http://localhost/randevu/verification.php?token=$verify_token' style='display: inline-block; background-color: #4B79A1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>E-postamı Doğrula</a>
                </p>
                <p>Eğer bu işlemi siz yapmadıysanız, lütfen bu e-postayı görmezden gelin.</p>
                <p>Saygılarımızla,<br>Laboratuvar Ekibi</p>
            </div>
        </body>
        </html>
        ";

        $mail->Body = $email_template;
        $mail->send();
    } catch (Exception $e) {
        echo "Mesaj gönderilemedi. Mailer Hatası: {$mail->ErrorInfo}";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['s_number'];
    $password = $_POST['password'];
    $verify_token = md5(rand());
    

    function properEmail($email) {
        $mail_regex = "/khas.edu.tr$/";
        return preg_match($mail_regex, $email);
    }

    function properPassword($password) {
        $password_regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        return preg_match($password_regex, $password);
    }

    $mail_query = "SELECT email FROM users WHERE email=? LIMIT 1";
    $stmt = $conn->prepare($mail_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['status'] = "Bu e-posta zaten kayıtlı.";
        header("Location: index.php");
        exit();
    } else {
        if (properEmail($email)) {
            if (properPassword($password)) {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $conn->prepare("INSERT INTO users (id, name, email, password, verify_token) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $number, $name, $email, $password_hash, $verify_token);

                if ($stmt->execute()) {
                    $_SESSION['user_id'] = $number; 
                    sendemail_verify($name, $email, $verify_token);
                    
                    $_SESSION['status'] = "Kayıt başarılı. Lütfen e-posta adresinizi doğrulayın.";
                    header("Location: index.php");
                    exit();
                } else {
                    $_SESSION['status'] = "Kayıt başarısız.";
                    header("Location: index.php");
                    exit();
                }

                $stmt->close();
            } else {
                $_SESSION['status'] = "Şifreniz en az bir büyük harf, bir küçük harf, bir rakam ve bir özel karakter içermelidir. Ayrıca en az 8 karakter uzunluğunda olmalıdır.";
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['status'] = "Mail adresiniz 'khas.edu.tr' alan adını içermelidir.";
            header("Location: index.php");
            exit();
        }
    }

    $stmt->close();
    $conn->close();
}
?>
