<?php
session_start();
include('config.php');
if(isset($_GET['token'])){
    $token = $_GET['token'];
    $verify_query = "SELECT verify_token, verify_status FROM users WHERE verify_token='$token' LIMIT 1";
    $verify_query_run = mysqli_query($conn, $verify_query);
//eeş
    if(mysqli_num_rows($verify_query_run) > 0){
        $row = mysqli_fetch_array($verify_query_run);
        //echo $row['verify_token'];
        if($row['verify_status'] == "0"){
            $clicked_token = $row['verify_token'];
            $update_query = "UPDATE users SET verify_status='1' WHERE verify_token='$clicked_token' LIMIT 1";
            $update_query_run = mysqli_query($conn, $update_query);
            if($update_query_run){
                $_SESSION['status'] = "Email verified successfully.";
                header("Location: index.php");
                exit(0);
            } else {
                $_SESSION['status'] = "Verification failed.";
                header("Location: index.php");
                exit(0); 
            }
        } else {
            $_SESSION['status'] = "Email already verified.";
            header("Location: index.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Error!";
        header("Location: index.php");
    }
} else {
    $_SESSION['status'] = "Not allowed.";
    header("Location: index.php");
}
echo "a";
?>
