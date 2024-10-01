<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Aylık Randevu Takvimi</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .day-cell {
            height: 100px;
            vertical-align: top;
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
        .navigation {
            text-align: center;
            margin-bottom: 20px;
        }
        .nav-button {
            background-color: #3056ff; /* Mavi arka plan */
            color: white; /* Beyaz yazı rengi */
            border: none; /* Çerçeve kaldır */
            padding: 7px 10px; /* İç boşluk küçültüldü */
            margin: 5px; /* Dış boşluk */
            text-decoration: none; /* Altı çizili metni kaldır */
            border-radius: 3px; /* Kenarları daha az yuvarlat */
            cursor: pointer; /* Fare imlecini düğme imlecine çevir */
            transition: background-color 0.3s; /* Renk geçiş animasyonu */
            font-size: 14px; /* Yazı boyutunu küçült */
        }
        .nav-button:hover {
            background-color: #003399; /* Fare üzerine gelindiğinde rengi koyulaştır */
        }

        .content {
            margin-left: 250px; /* Navbar genişliği kadar sola yasla */
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
        <h1>Aylık Randevu Takvimi</h1>
            <!-- Takvim Başlığı -->
        <h2 style="text-align:center;">
            <?php 
            $month = isset($_GET['month']) ? $_GET['month'] : date('m');
            $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
            echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?>
        </h2>

        <?php
        include 'config.php'; // Veritabanı bağlantı dosyası

        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
//eeş
        // Ay navigasyonu için linkler
        $prev_month = $month - 1;
        $next_month = $month + 1;
        $prev_year = $next_year = $year;
        if ($prev_month == 0) {
            $prev_month = 12;
            $prev_year = $year - 1;
        }
        if ($next_month == 13) {
            $next_month = 1;
            $next_year = $year + 1;
        }

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $startDay = date('w', mktime(0, 0, 0, $month, 1, $year));
        $startDay = $startDay == 0 ? 7 : $startDay; // Adjust for Sunday start

        echo "<div class='navigation'>";
        echo "<a href='?month=$prev_month&year=$prev_year' class='nav-button'>&laquo; Geri</a>";
        echo " | ";
        echo "<a href='?month=$next_month&year=$next_year' class='nav-button'>İleri &raquo;</a>";
        echo "</div>";

        echo "<table>";
        echo "<tr><th>Paz</th><th>Pzt</th><th>Sal</th><th>Çar</th><th>Per</th><th>Cum</th><th>Cmt</th></tr>";
        echo "<tr>";

        // Create padding for non-existent days in the first week
        for ($i = 1; $i < $startDay; $i++) {
            echo "<td></td>";
        }

        $currentDay = $startDay;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            if ($currentDay > 7) {
                echo "</tr><tr>";
                $currentDay = 1;
            }

            echo "<td class='day-cell'>";
            echo "<strong>$day</strong><br>";

            // Fetch reservations for the day with user name
            $date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '-' . str_pad($day, 2, "0", STR_PAD_LEFT);
            $sql = "SELECT r.*, u.name FROM reservations r JOIN users u ON r.user_id = u.id WHERE r.start_time LIKE '$date%' AND r.status = 'approved'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<hr><b>Cihaz:</b> " . $row["device_name"] . "<br>";
                    echo "<b>Saat:</b> " . date('H:i', strtotime($row["start_time"])) . " - " . date('H:i', strtotime($row["end_time"])) . "<br>";
                    echo "<b>Kullanıcı:</b> " . htmlspecialchars($row["name"]) . "<br>";
                }
            } else {
                echo "<hr>Randevu yok.";
            }

            echo "</td>";
            $currentDay++;
        }

        // Complete the row for the last week of the month, if necessary
        while ($currentDay <= 7) {
            echo "<td></td>";
            $currentDay++;
        }

        echo "</tr>";
        echo "</table>";
        ?>
    </div>
    
</body>
</html>
