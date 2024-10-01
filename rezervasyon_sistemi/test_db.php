<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Veritabanı bağlantı kodu burada
$servername = "localhost";
$username = "root";  // Veritabanı kullanıcı adınızı buraya yazın
$password = "";  // Veritabanı şifrenizi buraya yazın
$dbname = "rezervasyon_sistemi";  // Veritabanınızın adı

// Bağlantıyı oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
