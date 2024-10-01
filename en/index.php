<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Reservation System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .language-buttons {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }
        .language-buttons button {
            border: none;
            padding: 12px 45px;
            margin: 5px 0;
            background-color: #4B79A1;
            color: #ffffff;
            font-size: 12px;
            cursor: pointer;
            transition: transform 80ms ease-in, background-color 0.3s ease;
            border-radius: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .language-buttons button:hover {
            background-color: #365f91;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <!-- Language Switch Buttons -->
<div class="language-buttons">
    <button onclick="switchLanguage('tr')">TR</button>
    <button onclick="switchLanguage('en')">ENG</button>
</div>

<?php
session_start();
?>

<div class="container">
    <div class="form-container sign-up-container">
        <form action="register.php" method="POST">
            <h1>Sign Up</h1>
            <input type="text" placeholder="Full Name" name="name" required />
            <input type="email" placeholder="Email" name="email" required />
            <input type="text" placeholder="School Number" name="s_number" required />
            <input type="password" placeholder="Password" name="password" required />
            <button type="submit">Sign Up</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <form action="login.php" method="POST">
            <h1>Sign In</h1>
            <input type="email" placeholder="Email" name="email" required />
            <input type="password" placeholder="Password" name="password" required />
            <button type="submit">Sign In</button>
            <a href="forgot_password.php">Forgot your password?</a>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div id="register_div" class="overlay-panel overlay-right" style="z-index:9999;">
                <div id="reg_div">
                    <h1>Hello, Student!</h1>
                    <p>Enter your details and sign up.</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
                <div id="log_div" style="display:none;">
                    <h1>Hello</h1>
                    <p>If you have registered</p>
                    <p>Sign in</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(isset($_SESSION['status'])): //eeş?>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p><?php echo $_SESSION['status']; ?></p>
        </div>
    </div>
    <?php unset($_SESSION['status']); ?>
<?php endif; ?>

<script>
    document.getElementById('myModal').style.display = 'block';
    function closeModal() {
        document.getElementById('myModal').style.display = 'none';
    }


</script>

<script src="scripts.js"></script>
</body>
</html>
