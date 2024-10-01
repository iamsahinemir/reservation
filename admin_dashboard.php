<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
    //eeş
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
    }
    .navbar {
        width: 250px;
        height: 100vh;
        background-color: #007bff;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: fixed;
        top: 0;
        left: 0;
    }
    .navbar .logo {
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid #007bff;
    }
    .navbar .logo img {
        max-width: 100%;
        height: auto;
    }
    .navbar .menu {
        padding: 20px;
        flex-grow: 1;
    }
    .navbar .menu a {
        display: block;
        color: white;
        padding: 15px;
        text-decoration: none;
        transition: background 0.3s;
    }
    .navbar .menu a:hover {
        background-color: #0056b3;
        color: white;
    }
    .navbar .language-buttons {
        padding: 10px;
        display: flex;
        justify-content: center; /* Butonları ortaya hizalar */
        position: absolute;
        bottom: 80px; /* Dil butonlarının profil bölümünün üstünde kalması için */
        width: 100%;
        left:0;
    }
    .navbar .language-buttons button {
        border: none;
        padding: 12px 45px; /* Butonların genişliğini eski haline getirir */
        margin: 10px 5px;
        background-color: #4B79A1;
        color: #ffffff;
        font-size: 12px;
        cursor: pointer;
        transition: transform 80ms ease-in, background-color 0.3s ease;
        border-radius: 25px; /* Köşeleri yuvarlatır */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Hafif bir gölge ekler */
    }
    .navbar .language-buttons button:hover {
        background-color: #365f91; /* Hover durumunda buton rengini koyulaştırır */
        transform: scale(1.05); /* Hover durumunda butonu hafifçe büyütür */
    }
    .navbar .profile {
        padding: 20px;
        border-top: 1px solid #007bff;
        background-color: #444;
        text-align: center;
        position: absolute;
        bottom: 0;
        width: 100%;
    }
    .navbar .profile a {
        color: white;
        text-decoration: none;
        display: block;
        margin-top: 10px;
        padding: 10px;
        background-color: #007bff;
        border-radius: 5px;
        transition: background 0.3s;
    }
    .navbar .profile a:hover {
        background-color: #0056b3;
        color: white;
    }
    .content {
        margin-left: 250px;
        padding: 20px;
        width: calc(100% - 250px);
    }
    .content h1 {
        color: #333;
    }
</style>



    <script src="scripts.js"></script>
</head>
<body>
<div class="navbar">
    <div class="logo">
        <img src="49281182.jpeg" alt="Logo">
    </div>
    <div class="menu">
        <a href="admin_dashboard.php">Anasayfa</a>
            <a href="admin_users.php">Kullanıcılar</a>
            <a href="admin_reservations.php">Rezervasyonlar</a>
            <a href="admin_rez.php">Rezervasyon Yap</a>
            <a href="admin_takvim.php">Takvim</a>
        <div class="language-buttons">
            <button onclick="switchLanguage('tr')">TR</button>
            <button onclick="switchLanguage('en')">ENG</button>
        </div>
    </div>
    <div class="profile">
        <a href="logout.php">Çıkış</a>
    </div>
</div>
<div class="content">
    <h1>Admin Paneli</h1>
    <p>Merhaba, <?php echo $_SESSION['name']; ?>. Admin paneline hoş geldiniz!</p>
</div>
</body>
</html>
