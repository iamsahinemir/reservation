<?php
session_start();
include('config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'C:\xampp\htdocs\rezervasyon_sistemi\PHPMailer\src\Exception.php';
require 'C:\xampp\htdocs\rezervasyon_sistemi\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\rezervasyon_sistemi\PHPMailer\src\SMTP.php';

function sendemail_verify($name, $email, $verify_token) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp-mail.outlook.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'esad.emir34@stu.khas.edu.tr';
        $mail->Password   = '@m!R.12345'; // Kendi şifrenizi buraya ekleyin
        $mail->SMTPSecure = 'STARTTLS';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('esad.emir34@stu.khas.edu.tr', 'Laboratuvar Rezervasyon Sistemi');
        $mail->addAddress($email);
//eeş
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $email_template = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.5;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9;'>
                <h2 style='text-align: center; color: #4B79A1;'>Registration Process</h2>
                <p>Hello, $name!</p>
                <p>Thank you for registering with the CCIP Lab Reservation System. Please click the link below to verify your email address:</p>
                <p style='text-align: center;'>
                    <a href='http://localhost/randevu/verification.php?token=$verify_token' style='display: inline-block; background-color: #4B79A1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Verify My Email</a>
                </p>
                <p>If you did not initiate this request, please ignore this email.</p>
                <p>Best regards,<br>CCIP Lab Team</p>
            </div>
        </body>
        </html>
        ";

        $mail->Body = $email_template;
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
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
        $_SESSION['status'] = "This email is already registered.";
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
                    
                    $_SESSION['status'] = "Registration successful. Please verify your email address.";
                    header("Location: index.php");
                    exit();
                } else {
                    $_SESSION['status'] = "Registration failed.";
                    header("Location: index.php");
                    exit();
                }

                $stmt->close();
            } else {
                $_SESSION['status'] = "Your password must contain at least one uppercase letter, one lowercase letter, one number, and one special character. It must also be at least 8 characters long.";
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['status'] = "Your email address must contain the 'khas.edu.tr' domain.";
            header("Location: index.php");
            exit();
        }
    }

    $stmt->close();
    $conn->close();
}
?>
