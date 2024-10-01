<?php
session_start();
if (!isset($_SESSION['user_id'])) {
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
    <title>Takvim</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    .alert {
    padding: 15px;
    background-color: #f44336; /* Red */
    color: white;
    margin-bottom: 20px;
}

.alert-info {
    background-color: #2196F3; /* Blue */
}

.alert a {
    color: yellow;
    text-decoration: underline;
}

.alert a:hover {
    color: white;
}


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

        .navbar .profile {
            padding: 20px;
            border-top: 1px solid #575757;
            background-color: #444;
            position: relative;
        }

        .navbar .profile a {
            color: white;
            text-decoration: none;
        }

        .navbar .dropdown-content {
            display: none;
            position: absolute;
            background-color: #444;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            bottom: 100%;
        }

        .navbar .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            top: 100%;
        }

        .navbar .dropdown-content a:hover {
            background-color: #c1cdff;
            color: #3056ff;
        }

        .navbar .profile:hover .dropdown-content {
            display: block;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .content h1 {
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group select, .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #4B79A1;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #abd5fb;
            color: #3056ff;
        }

        .radio-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .radio-group label {
            flex: 1;
            text-align: center;
            padding: 10px;
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .radio-group input {
            display: none;
        }

        .radio-group input:checked + label {
            background-color: #4B79A1;
            color: white;
        }

        .hidden {
            display: none;
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
    border-radius: 25px; /* Adds rounded corners */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Adds a subtle shadow */
}

.language-buttons button:hover {
    background-color: #365f91; /* Slightly darker blue on hover */
    transform: scale(1.05); /* Slightly enlarge the button on hover */
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
            <a href="user.php">Anasayfa</a>
            <a href="devices.php">Cihazlar</a>
            <a href="calendar.php">Rezervasyon</a>
            <a href="takvim.php">Takvim</a>
        </div>
        <div class="language-buttons">
            <button onclick="switchLanguage('tr')">TR</button>
            <button onclick="switchLanguage('en')">ENG</button>
        </div>
        <div class="profile">
            <a href="#">Profil</a>
            <div class="dropdown-content">
                <a href="profile.php">Bilgilerim</a>
                <a href="reservations.php">Rezervasyonlarım</a>
                <a href="logout.php">Çıkış</a>
            </div>
        </div>
    </div>
    <div class="content">
    <?php if (isset($_SESSION['alert'])): ?>
        <div class="alert alert-info" role="alert">
            <?php echo $_SESSION['alert']; unset($_SESSION['alert']); ?>
        </div>
    <?php endif; ?>
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
    window.onload = function() {
    // Mevcut yüklenme fonksiyonlarını çağır
    toggleDeviceDropdown();

    // Alert kutularını otomatik gizle
    setTimeout(function() {
        var alertElements = document.querySelectorAll('.alert');
        alertElements.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 3000); // 3 saniye sonra
};
</script>

</body>
</html>
