<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
include('config.php');


//eeş
// Kullanıcı bilgilerini al
$user_id = $_SESSION['user_id'];
$sql = "SELECT account_status FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// account_status değişkenini kontrol et
$account_status = 0; // Varsayılan değer
if ($user !== null && isset($user['account_status'])) {
    $account_status = $user['account_status'];
}

?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervasyonlarım</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
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
    display: flex;
    justify-content: center;
    padding: 20px;
}

.language-buttons button {
    border: none;
    padding: 12px 45px;
    margin: 10px 5px;
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

.navbar .profile {
    padding: 20px;
    border-top: 1px solid #007bff;
    background-color: #444;
    text-align: center;
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
}

.container {
    margin-left: 250px;
    padding: 20px;
    width: calc(100% - 250px);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    min-height: 100vh;
    background-color: #fff;
}

.container h1 {
    margin-top: 20px;
    text-align: center;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
    width: 100%;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group select, .form-group input {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
    border-radius: 5px;
    border: 1px solid #ddd;
    background-color: #fff;
}

.form-group button {
    padding: 10px 20px;
    background-color: #4B79A1;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    width: 100%;
}

.form-group button:hover {
    background-color: #365f91;
}

.radio-group {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    width: 100%;
}

.radio-group label {
    flex: 1;
    text-align: center;
    padding: 10px;
    background-color: #f2f2f2;
    border: 1px solid #ddd;
    cursor: pointer;
    transition: background-color 0.3s;
    margin: 0 5px;
}

.radio-group input {
    display: none;
}

.radio-group input:checked + label {
    background-color: #4B79A1;
    color: white;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background-color: #f9f9f9;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    font-size: 1.1em;
}

th {
    background-color: #007bff;
    color: white;
    text-transform: uppercase;
}

tr:hover {
    background-color: #f1f1f1;
}

button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    text-transform: uppercase;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

.alert {
    margin: 20px 0;
    padding: 15px;
    border-radius: 5px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.hidden {
    display: none;
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
        </div>
        <div class="language-buttons">
            <button onclick="switchLanguage('tr')">TR</button>
            <button onclick="switchLanguage('en')">ENG</button>
        </div>
        <div class="profile">
            <a href="logout.php">Çıkış</a>
        </div>
    </div>
       <div class="content">
    <h1>Rezervasyon Oluşturma</h1>
    <form id="reservationForm" action="make_reservation.php" method="POST">
        <div class="radio-group">
            <?php if ($account_status == 1): ?>
                <input type="radio" id="computer" name="device_type" value="computer" onchange="toggleDeviceDropdown()" checked>
                <label for="computer">Bilgisayar</label>
            <?php endif; ?>
            <input type="radio" id="desk" name="device_type" value="desk" onchange="toggleDeviceDropdown()" <?php echo ($account_status != 1) ? 'checked' : ''; ?>>
            <label for="desk">Masa</label>
        </div>

        <?php if ($account_status == 1): ?>
            <div class="form-group" id="computerDropdown">
                <label for="computers">Bilgisayar Seçin:</label>
                <select id="computers" name="computers">
                    <?php
                    $sql = "SELECT id, name FROM devices WHERE type = 'computer'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="form-group" id="deskDropdown">
            <label for="desks">Masa Seçin:</label>
            <select id="desks" name="desks">
                <?php
                $sql = "SELECT id, name FROM devices WHERE type = 'desk'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                }

                $conn->close();
                ?>
            </select>
        </div>

        <div id="reservationDetails">
            <div class="form-group">
                <label for="date">Tarih Seçin:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="start_time">Başlangıç Saati:</label>
                <select id="start_time" name="start_time" required>
                    <?php
                    for ($hour = 0; $hour < 24; $hour++) {
                        for ($minute = 0; $minute < 60; $minute += 30) {
                            $time = sprintf('%02d:%02d', $hour, $minute);
                            echo "<option value='$time'>$time</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="end_time">Bitiş Saati:</label>
                <select id="end_time" name="end_time" required>
                    <?php
                    for ($hour = 0; $hour < 24; $hour++) {
                        for ($minute = 0; $minute < 60; $minute += 30) {
                            $time = sprintf('%02d:%02d', $hour, $minute);
                            echo "<option value='$time'>$time</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="aciklama">Açıklama</label>
                <input type="text" id="aciklama" name="aciklama" placeholder="Neden bu cihazı kullanacaksınız?" required>
            </div>

            <div class="form-group">
                <button type="submit">Rezervasyon Yap</button>
            </div>
        </div>

        <?php if ($account_status == 0): ?>
            <div class="alert alert-info" role="alert">
                Bilgisayar hesabı oluşturmak için lütfen <a href="mailto:ccip@khas.edu.tr">ccip@khas.edu.tr</a> hesabına mail atın.
            </div>
        <?php endif; ?>
    </form>
</div>

<script>
    function toggleDeviceDropdown() {
        var computerDropdown = document.getElementById('computerDropdown');
        var deskDropdown = document.getElementById('deskDropdown');
        var reservationDetails = document.getElementById('reservationDetails');

        if (document.getElementById('computer') && document.getElementById('computer').checked) {
            computerDropdown.classList.remove('hidden');
            deskDropdown.classList.add('hidden');
        } else {
            deskDropdown.classList.remove('hidden');
            computerDropdown.classList.add('hidden');
        }

        reservationDetails.classList.remove('hidden');
    }

    window.onload = toggleDeviceDropdown;
</script>

</body>
</html>
