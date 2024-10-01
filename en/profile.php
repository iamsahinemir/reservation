<?php
session_start();
include('config.php');

// Check user session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get data from session
$email = $_SESSION['email'];
$old_user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

// Update profile information
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $new_user_id = $_POST['number'];

    // Perform update in the database
    $conn->begin_transaction();

    try {
        // Temporarily disable foreign key checks
        $conn->query("SET FOREIGN_KEY_CHECKS=0");

        // Update the `users` table
        $sql = "UPDATE users SET name = ?, id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $new_user_id, $old_user_id);
        $stmt->execute();

        // Update the `reservations` table
        $sql = "UPDATE reservations SET user_id = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $new_user_id, $old_user_id);
        $stmt->execute();

        // Re-enable foreign key checks
        $conn->query("SET FOREIGN_KEY_CHECKS=1");

        $conn->commit();

        // Update session with new information
        $_SESSION['user_id'] = $new_user_id;
        $_SESSION['name'] = $name;
        
        $success_message = "Profile information successfully updated.";
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = "An error occurred while updating profile information: " . $e->getMessage();
    }//eeş

    $stmt->close();
} else {
    // Fetch current user information
    $sql = "SELECT name, email, id FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $old_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $email = $row['email'];
        $new_user_id = $row['id']; // This value will be displayed as the number in the form
    } else {
        $error_message = "Failed to retrieve user information.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
            border-top: 1px solid #575757;
            background-color: #444;
            position: relative;
        }

        .navbar .profile a {
            color: white;
            text-decoration: none;
        }

        .navbar .profile a:hover {
            background-color: #c1cdff;
            color: #3056ff;
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

        .profile-info {
            max-width: 400px;
            margin: 20px auto;
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .profile-info h2 {
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .profile-info p {
            margin-bottom: 10px;
        }

        .message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
    <script>
        // Hide alerts after 3 seconds when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var messages = document.querySelectorAll('.message');
                messages.forEach(function(message) {
                    message.style.display = 'none';
                });
            }, 3000); // 3 seconds (3000 milliseconds)
        });

    </script>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="49281182.jpeg" alt="Logo">
        </div>
        <div class="menu">
            <a href="user.php">Home</a>
            <a href="devices.php">Devices</a>
            <a href="calendar.php">Reservations</a>
            <a href="takvim.php">Calendar</a>
        </div>
        <div class="language-buttons">
            <button onclick="switchLanguage('tr')">TR</button>
            <button onclick="switchLanguage('en')">ENG</button>
        </div>
        <div class="profile">
            <a href="#">Profile</a>
            <div class="dropdown-content">
                <a href="profile.php">My Information</a>
                <a href="reservations.php">My Reservations</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    <div class="content">
        <h1>My Profile</h1>
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <div class="profile-info">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="number">Number:</label>
                    <input type="text" id="number" name="number" value="<?php echo htmlspecialchars($new_user_id); ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
