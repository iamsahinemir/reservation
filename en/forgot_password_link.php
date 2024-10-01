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

function send_password_reset($get_name, $get_email, $token){
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp-mail.outlook.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'YOUR_MAIL'; // ADD YOUR REAL E-MAIL
        $mail->Password   = 'YOUR_PASSWORD'; //ADD YOUR REAL PASSWORD
        $mail->SMTPSecure = 'STARTTLS';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('YOUR_MAIL', 'Laboratory Reservation System');
        $mail->addAddress($get_email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $email_template = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.5;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9;'>
                <h2 style='text-align: center; color: #333;'>Password Reset Request</h2>
                <p>Hello, $get_name!</p>
                <p>You have requested a password reset for your account. Click the link below to reset your password:</p>
                <p style='text-align: center;'>
                    <a href='http://localhost/randevu/password_reset.php?token=$token&email=$get_email' style='display: inline-block; background-color: #4B79A1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Reset Your Password</a>
                </p>
                <p>If you did not make this request, please ignore this email.</p>
                <p>Best regards,<br>Laboratory Team</p>
            </div>
        </body>
        </html>
        ";

        $mail->Body = $email_template;
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Error: {$mail->ErrorInfo}";
    }
}

function properPassword($password) {
    $password_regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    return preg_match($password_regex, $password);
}

if(isset($_POST['reset'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $token = md5(rand());

    $check_email = "SELECT name, email FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $get_name = $row['name'];
        $get_email = $row['email'];
        
        $update_token = "UPDATE users SET verify_token = ? WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($update_token);
        $stmt->bind_param("ss", $token, $get_email);
        $update_token_run = $stmt->execute();

        if($update_token_run){
            send_password_reset($get_name, $get_email, $token);
            $_SESSION['status'] = "Password reset link has been sent to your email address.";
            header("Location: index.php");
            exit(0);
        } else {
            echo "Error. #1";
            header("Location: index.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Email address not found.";
        header("Location: index.php");
        exit(0);
    }
}

if(isset($_POST['password_update'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $token = mysqli_real_escape_string($conn, $_POST['password_token']);

    if(!empty($token)){
        if(!empty($email) && !empty($new_password) && !empty($confirm_password)){
            $check_token = "SELECT verify_token FROM users WHERE verify_token='$token' LIMIT 1";
            $check_token_run = mysqli_query($conn, $check_token);
            if(mysqli_num_rows($check_token_run) > 0){
                if($new_password == $confirm_password){
                    if(properPassword($new_password)){
                        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                        $update_password = "UPDATE users SET password='$hashed_password' WHERE verify_token='$token' LIMIT 1";
                        $update_password_run = mysqli_query($conn, $update_password);
                    }
                    
                    if($update_password_run){
                        $_SESSION['status'] = "Password has been successfully updated.";
                        header("Location: index.php");
                        exit(0); 
                    } else {
                        $_SESSION['status'] = "Password update failed.";
                        header("Location: forgot_password.php?token=$token&email=$email");
                        exit(0); 
                    }
                } else {
                    $_SESSION['status'] = "Please enter matching passwords.";
                    header("Location: forgot_password.php?token=$token&email=$email");
                    exit(0); 
                }
            } else {
                $_SESSION['status'] = "Invalid token.";
                header("Location: forgot_password.php?token=$token&email=$email");
                exit(0); 
            }
        } else {
            $_SESSION['status'] = "Please fill in all fields.";
            header("Location: forgot_password.php?token=$token&email=$email");
            exit(0); 
        }
    } else {
        $_SESSION['status'] = "Token not found.";
        header("Location: forgot_password.php");
        exit(0);
    }
}
?>
