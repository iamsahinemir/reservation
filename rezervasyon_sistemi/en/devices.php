<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devices</title>
    <link rel="stylesheet" href="styles.css">
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

        .navbar .profile {
            padding: 20px;
            border-top: 1px solid #c1cdff;
            background-color: #444;
            position: relative;
        }

        .navbar .profile a {
            color: white;
            text-decoration: none;
        }

        /* Dropdown styles */
        .navbar .dropdown-content {
            display: none;
            position: absolute;
            background-color: #444;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            bottom: 100%; /* Position the dropdown upwards */
        }

        .navbar .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            top: 100%; /* Ensure the dropdown appears below the profile link */
        }

        .navbar .dropdown-content a:hover {
            background-color: #c1cdff;
            color: #3056ff;
        }

        /* Show dropdown on hover */
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

        .device-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .device-card {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1px;
            text-align: center;
        }

        .device-card img {
            width: 30%; /* Adjust the image width as a percentage */
            height: auto;
            border-radius: 8px;
            object-fit: cover; /* Ensures the image fits within its container */
        }

        .device-card h2 {
            font-size: 1.5em;
            margin: 10px 0;
        }

        .device-card p {
            color: #666;
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
/*eeş*/
    </style>
    <script src="scripts.js"></script>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="49281182.jpeg" alt="Logo">
        </div>
        <div class="menu">
            <a href="user.php">Home</a>
            <a href="devices.php">Devices</a>
            <a href="calendar.php">Reservation</a>
            <a href="takvim.php">Calendar</a>
        </div>
        <div class="language-buttons">
            <button onclick="switchLanguage('tr')">TR</button>
            <button onclick="switchLanguage('en')">ENG</button>
        </div>
        <div class="profile">
            <a href="#">Profile</a>
            <div class="dropdown-content">
                <a href="profile.php">My Info</a>
                <a href="reservations.php">My Reservations</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    <div class="content">
        <h1>Devices</h1>
        <div class="device-grid">
            <div class="device-card">
                <img src="foto\masa_1.jpg" alt="Desk 1">
                <h2>Desk 1</h2>
                <p>Desk</p>
            </div>
            <div class="device-card">
                <img src="foto\masa_2.jpg" alt="Desk 2">
                <h2>Desk 2</h2>
                <p>Desk</p>
            </div>
            <div class="device-card">
                <img src="foto\masa_3.jpg" alt="Desk 3">
                <h2>Desk 3</h2>
                <p>Desk</p>
            </div>
            <div class="device-card">
                <img src="foto\fat.jpg" alt="Fat-Man">
                <h2>Fat-Man</h2>
                <p>Admin User: ccipfm</p>
                <p>IP Address: 10.1.11.25</p>
                <p>Operating System: Fedora Linux (GNOME)</p>
                <p>CPU: Intel i9-10900X 4.70 GHz</p>
                <p>GPU: 3x NVIDIA A4000</p>
            </div>
            <div class="device-card">
                <img src="foto\lit.jpg" alt="Little-Boy">
                <h2>Little-Boy</h2>
                <p>Admin User: ccip</p>
                <p>IP Address: 10.1.11.28</p>
                <p>Operating System: Fedora Linux (KDE)</p>
                <p>CPU: Intel i9-7900X 4.50 GHz</p>
                <p>GPU: 2x NVIDIA GTX1080TI</p>
            </div>
        </div>
    </div>
</body>
</html>
