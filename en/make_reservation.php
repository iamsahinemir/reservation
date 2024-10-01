<?php
session_start();
include('config.php');



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $device_id = ($_POST['device_type'] == 'computer') ? $_POST['computers'] : $_POST['desks'];

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
    }//eeş
    $stmt->close();

    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $description = $_POST['aciklama'];

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        $start_datetime = $date . ' ' . $start_time;
        $end_datetime = $date . ' ' . $end_time;

        // Check for valid date and time
        $current_datetime = date('Y-m-d H:i:s');
        if ($start_datetime < $current_datetime || $end_datetime < $current_datetime) {
            $_SESSION['alert'] = "You cannot make a reservation in the past.";
            header("Location: calendar.php");
            exit();
        } elseif ($start_time >= $end_time) {
            $_SESSION['alert'] = "Start time must be before the end time.";
            header("Location: calendar.php");
            exit();
        } else {
            $sql = "SELECT * FROM reservations WHERE device_id = ? AND ((start_time <= ? AND end_time >= ?) OR (start_time <= ? AND end_time >= ?))";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $device_id, $start_datetime, $start_datetime, $end_datetime, $end_datetime);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['alert'] = "There is already a reservation for this device in the selected date and time range.";
                header("Location: calendar.php");
                exit();
            } else {
                $sql = "INSERT INTO reservations (user_id, device_id, device_name, start_time, end_time, aciklama, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iissss", $user_id, $device_id, $device_name, $start_datetime, $end_datetime, $description);
                if ($stmt->execute()) {
                    $_SESSION['alert'] = "Reservation created successfully.";
                    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
                        header("Location: reservations.php");
                        exit();
                    } else {
                        header("Location: admin_reservations.php");
                        exit();
                    }
                } else {
                    $_SESSION['alert'] = "Error: " . $stmt->error;
                    header("Location: reservations.php");
                    exit();
                }
            }
            $stmt->close();
        }
    } else {
        $_SESSION['alert'] = "User session not found. Please log in.";
        header("Location: index.php");
        exit();
    }
}

$conn->close();
?>
