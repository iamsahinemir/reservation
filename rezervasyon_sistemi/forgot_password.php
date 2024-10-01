<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifremi Unuttum</title>
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

        .message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <h2>Şifremi Unuttum</h2>
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; //eeş?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="forgot_password_link.php" method="post">
            <div class="form-group">
                <label for="email">E-posta Adresi:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <button type="submit" name='reset'>Şifre Sıfırlama Bağlantısı Gönder</button>
            </div>
        </form>
    </div>
</body>
</html>
