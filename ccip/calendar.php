<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Takvim</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            width: 250px;
            height: 100vh;
            background-color: #333;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
        }

        .navbar .menu {
            padding: 20px;
        }

        .navbar .menu a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .navbar .menu a:hover {
            background-color: #575757;
        }

        .navbar .profile {
            padding: 20px;
            border-top: 1px solid #575757;
            background-color: #444;
        }

        .navbar .profile a {
            color: white;
            text-decoration: none;
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

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #575757;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="menu">
            <a href="user.html">Anasayfa</a>
            <a href="devices.html">Cihazlar</a>
            <a href="calendar.php">Takvim</a>
        </div>
        <div class="profile">
            <a href="profile.html">Profil</a>
        </div>
    </div>
    <div class="content">
        <form id="reservationForm" action="make_reservation.php" method="POST">
            <div class="form-group">
                <label for="device">Cihaz Seçin:</label>
                <select id="device" name="device">
                    <!-- PHP ile cihazların listesini buraya ekleyeceğiz -->
                    <?php
                    // Veritabanı bağlantısını burada yapınız
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "rezervasyon_sistemi";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Bağlantı hatası: " . $conn->connect_error);
                    }

                    $sql = "SELECT id, name FROM devices";
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
            <div class="form-group">
                <label for="date">Tarih Seçin:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="start_time">Başlangıç Saati:</label>
                <input type="time" id="start_time" name="start_time" required>
            </div>
            <div class="form-group">
                <label for="end_time">Bitiş Saati:</label>
                <input type="time" id="end_time" name="end_time" required>
            </div>
            <div class="form-group">
                <button type="submit">Rezervasyon Yap</button>
            </div>
        </form>
    </div>
</body>
</html>
