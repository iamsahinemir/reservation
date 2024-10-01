<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rezervasyon_sistemi";

$conn = new mysqli($servername, $username, $password, $dbname);
//eeş
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
