<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $device_id = ($_POST['device_type'] == 'computer') ? $_POST['computers'] : $_POST['desks'];
//eeş
    // Get the device name from the database
    $sql = "SELECT name FROM devices WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $device_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $device_name = $row['name'];
    } else {
        $device_name = "";
    }
    $stmt->close();

    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $aciklama = $_POST['aciklama'];

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        $start_datetime = $date . ' ' . $start_time;
        $end_datetime = $date . ' ' . $end_time;

        // Geçerli tarih ve saat kontrolü
        $current_datetime = date('Y-m-d H:i:s');
        if ($start_datetime < $current_datetime || $end_datetime < $current_datetime) {
            $_SESSION['alert'] = "Geçmişe dönük rezervasyon yapılamaz.";
            header("Location: calendar.php");
            exit();
        } elseif ($start_time >= $end_time) {
            $_SESSION['alert'] = "Başlangıç zamanı bitiş zamanından önce olmalıdır.";
            header("Location: calendar.php");
            exit();
        } else {
            $sql = "SELECT * FROM reservations WHERE device_id = ? AND ((start_time <= ? AND end_time >= ?) OR (start_time <= ? AND end_time >= ?))";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $device_id, $start_datetime, $start_datetime, $end_datetime, $end_datetime);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['alert'] = "Seçtiğiniz tarih ve saat aralığında bu cihaz için zaten bir rezervasyon var.";
                header("Location: calendar.php");
                exit();
            } else {
                $sql = "INSERT INTO reservations (user_id, device_id, device_name, start_time, end_time, aciklama, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iissss", $user_id, $device_id, $device_name, $start_datetime, $end_datetime, $aciklama);
                if ($stmt->execute()) {
                    $_SESSION['alert'] = "Rezervasyon başarıyla oluşturuldu.";
                    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
                        header("Location: reservations.php");
                        exit();
                    } else {
                        header("Location: admin_reservations.php");
                        exit();
                    }
                } else {
                    $_SESSION['alert'] = "Hata: " . $stmt->error;
                    header("Location: reservations.php");
                    exit();
                }
            }
            $stmt->close();
        }
    } else {
        $_SESSION['alert'] = "Kullanıcı oturumu bulunamadı. Lütfen giriş yapınız.";
        header("Location: index.php");
        exit();
    }
}

$conn->close();
?>
