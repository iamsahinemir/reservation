<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rezervasyon_sistemi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $device_id = $_POST['device'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $user_id = $_SESSION['users_id']; // Kullanıcı ID'sini oturumdan alıyoruz

    // Tarih ve saatleri datetime formatına dönüştür
    $start_datetime = $date . ' ' . $start_time;
    $end_datetime = $date . ' ' . $end_time;

    // Çakışma kontrolü
    $sql = "SELECT * FROM reservations WHERE device_id = ? AND ((start_time <= ? AND end_time >= ?) OR (start_time <= ? AND end_time >= ?))";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $device_id, $start_datetime, $start_datetime, $end_datetime, $end_datetime);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Seçtiğiniz tarih ve saat aralığında bu cihaz için zaten bir rezervasyon var.";
    } else {
        // Rezervasyonu ekle
        $sql = "INSERT INTO reservations (user_id, device_id, start_time, end_time) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $user_id, $device_id, $start_datetime, $end_datetime);

        if ($stmt->execute()) {
            echo "Rezervasyon başarılı!";
        } else {
            echo "Hata: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>
