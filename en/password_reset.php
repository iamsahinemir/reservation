<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
        }

        .forgot-password-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
        }

        .forgot-password-container h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #3056ff;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }

        .form-group button:hover {
            background-color: #c1cdff;
            color: #3056ff;
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <h2>Forgot Password</h2>
        <form action="forgot_password_link.php" method="post">
            <input type="hidden" name="password_token" value="<?php if (isset($_GET['token'])){echo $_GET['token'];}//eeş?>">
            <div class="form-group">
                <label for="email" type="hidden"> </label>
                <input type="hidden" id="email" name="email" value="<?php if (isset($_GET['email'])){echo $_GET['email'];}?>" required>
                <label for="password">New Password: </label>
                <input type="password" id="new_password" name="new_password" placeholder="Please enter your new password" required>
                <label for="password">Confirm New Password: </label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Please re-enter your new password" required>
            </div>
            <div class="form-group">
                <button class="ghost" type="submit" name='password_update'>Reset Password</button>
            </div>
        </form>
    </div>
</body>
</html>
