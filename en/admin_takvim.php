<?php
//eeş
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
        justify-content: center; /* Centers the buttons */
        position: absolute;
        bottom: 80px; /* Places the language buttons above the profile section */
        width: 100%;
        left:0;
    }
    .navbar .language-buttons button {
        border: none;
        padding: 12px 45px; /* Restores the buttons' width */
        margin: 10px 5px;
        background-color: #4B79A1;
        color: #ffffff;
        font-size: 12px;
        cursor: pointer;
        transition: transform 80ms ease-in, background-color 0.3s ease;
        border-radius: 25px; /* Rounds the corners */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Adds a slight shadow */
    }
    .navbar .language-buttons button:hover {
        background-color: #365f91; /* Darkens the button color on hover */
        transform: scale(1.05); /* Slightly enlarges the button on hover */
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
        margin-left: 250px; /* Moves content to the right by the width of the navbar */
        padding: 20px;
    }
    .content h1 {
        color: #333;
    }
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
    .nav-button {
        background-color: #3056ff; /* Blue background */
        color: white; /* White text */
        border: none; /* Removes border */
        padding: 7px 10px; /* Reduces padding */
        margin: 5px; /* Adds margin */
        text-decoration: none; /* Removes underline from text */
        border-radius: 3px; /* Slightly rounds corners */
        cursor: pointer; /* Changes cursor to pointer */
        transition: background-color 0.3s; /* Adds background color transition */
        font-size: 14px; /* Reduces font size */
    }
    .nav-button:hover {
        background-color: #003399; /* Darkens background on hover */
    }
    .navigation {
        text-align: center;
        margin-bottom: 20px;
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
        <a href="admin_dashboard.php">Home</a>
        <a href="admin_users.php">Users</a>
        <a href="admin_reservations.php">Reservations</a>
        <a href="admin_rez.php">Make a Reservation</a>
        <a href="admin_takvim.php">Calendar</a>
        <div class="language-buttons">
            <button onclick="switchLanguage('tr')">TR</button>
            <button onclick="switchLanguage('en')">ENG</button>
        </div>
    </div>
    <div class="profile">
        <a href="logout.php">Logout</a>
    </div>
</div>
<div class="content">
        <h1>Monthly Reservation Calendar</h1>
        <!-- Calendar Heading -->
        <h2 style="text-align:center;">
            <?php
            $month = isset($_GET['month']) ? $_GET['month'] : date('m');
            $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
            echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?>
        </h2>

        <?php
        include 'config.php'; // Database connection file

        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

        // Links for month navigation
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
        echo "<a href='?month=$prev_month&year=$prev_year' class='nav-button'>&laquo; Previous</a>";
        echo " | ";
        echo "<a href='?month=$next_month&year=$next_year' class='nav-button'>Next &raquo;</a>";
        echo "</div>";

        echo "<table>";
        echo "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";
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
                    echo "<hr><b>Device:</b> " . $row["device_name"] . "<br>";
                    echo "<b>Time:</b> " . date('H:i', strtotime($row["start_time"])) . " - " . date('H:i', strtotime($row["end_time"])) . "<br>";
                    echo "<b>User:</b> " . htmlspecialchars($row["name"]) . "<br>";
                    // Add clickable link
                    echo "<a href='admin_reservations.php?reservation_id=" . $row['id'] . "' class='nav-button'>View Details</a>";
                }
            } else {
                echo "<hr>No reservations.";
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
