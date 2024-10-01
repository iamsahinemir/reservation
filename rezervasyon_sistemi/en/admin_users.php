<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

include('config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'YOUR_PATH\PHPMailer\src\Exception.php';
require 'YOUR_PATH\PHPMailer\src\PHPMailer.php';
require 'YOUR_PATH\PHPMailer\src\SMTP.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add new user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['update_role']) && !isset($_POST['update_status'])) {
    // Check if form data is available
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $s_number = isset($_POST['s_number']) ? $_POST['s_number'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';

    // Check if required fields are not empty
    if (!empty($name) && !empty($email) && !empty($s_number) && !empty($password) && !empty($role)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (id, name, email, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $s_number, $name, $email, $password_hashed, $role);

        if ($stmt->execute()) {
            $_SESSION['status'] = "User added successfully!";

            // Send email
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp-mail.outlook.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'YOUR_MAIL'; // ADD YOUR REAL E-MAIL
                $mail->Password   = 'YOUR_PASSWORD'; //ADD YOUR REAL PASSWORD
                $mail->SMTPSecure = 'STARTTLS';
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                $mail->setFrom('YOUR_MAIL', 'Laboratory Reservation System');
                $mail->addAddress($email, $name);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Registration Successful';
                $mail->Body    = "
                <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f7f7f7;
                            padding: 20px;
                            color: #333;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: #fff;
                            padding: 20px;
                            border-radius: 10px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
                        .header {
                            background-color: #4CAF50;
                            padding: 10px;
                            border-radius: 5px 5px 0 0;
                            color: white;
                            text-align: center;
                        }
                        .content {
                            padding: 20px;
                        }
                        .footer {
                            text-align: center;
                            font-size: 12px;
                            color: #777;
                            margin-top: 20px;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>Registration Successful</h2>
                        </div>
                        <div class='content'>
                            <p>Hello, <strong>{$name}</strong>!</p>
                            <p>Thank you for registering with the CCIP Laboratory Reservation System. To log in to your account, please <a href='http://localhost/randevu/ccip/'>click here</a>.</p>
                            <p>Best regards,</p>
                            <p><strong>The CCIP Laboratory Team</strong></p>
                        </div>
                        <div class='footer'>
                            <p>This message was generated automatically, please do not reply.</p>
                        </div>
                    </div>
                </body>
                </html>";

                $mail->send();
            } catch (Exception $e) {
                $_SESSION['status'] = "User added successfully, but email could not be sent: {$mail->ErrorInfo}";
            }
        } else {
            $_SESSION['status'] = "Error adding user: " . $conn->error;
        }
        $stmt->close();
    } else {
        $_SESSION['status'] = "Please fill out all fields.";
    }
}

// Update user account status
if (isset($_POST['update_status'])) {
    $user_id = $_POST['user_id'];
    $account_status = $_POST['account_status'];

    $sql = "UPDATE users SET account_status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $account_status, $user_id);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Account status updated successfully.";
    } else {
        $_SESSION['status'] = "Failed to update account status.";
    }

    header("Location: admin_users.php");
    exit();
}

// Update user roles
if (isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];

    $sql = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_role, $user_id);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Role updated successfully.";
    } else {
        $_SESSION['status'] = "Failed to update role.";
    }

    header("Location: admin_users.php");
    exit();
}
//eeş
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch users from the database
$sql = "SELECT id, name, email, role, account_status FROM users LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
if (!$result) {
    die("Error executing query: " . $conn->error);
}

$total_sql = "SELECT COUNT(*) as total FROM users";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
        }

        .content h1 {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            margin-top: 0;
            text-align: center;
        }

        .form-container {
            padding: 10pt;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%;
        }

        .user-actions form {
            display: inline;
        }

        .user-actions button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .user-actions button:hover {
            background-color: #ff9999;
            color: white;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .role-select {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .role-select form {
            margin: 0;
        }

        .role-select select {
            margin-right: 10px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
            text-align: center;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
            text-align: center;
        }

        .navbar .language-buttons {
            padding: 10px;
            display: flex;
            justify-content: center;
            position: absolute;
            bottom: 80px;
            width: 100%;
            left: 0;
        }

        .navbar .language-buttons button {
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

        .navbar .language-buttons button:hover {
            background-color: #365f91;
            transform: scale(1.05);
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
        <h1>User Management</h1>
        <?php if (isset($_SESSION['status'])): ?>
            <div id="status-alert" class="alert <?php echo strpos($_SESSION['status'], 'successfully') !== false ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo $_SESSION['status']; unset($_SESSION['status']); ?>
            </div>
        <?php endif; ?>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-4 form-container">
                    <form action="admin_users.php" method="POST">
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="Name" required />
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Email" required />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="s_number" placeholder="Student Number" required />
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Password" required />
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Add User</button>
                    </form>
                </div>

                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Account Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td>
                                        <form action="admin_users.php" method="POST">
                                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                            <select name="role" class="form-control" onchange="this.form.submit()">
                                                <option value="user" <?php echo $row['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                                                <option value="admin" <?php echo $row['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                            </select>
                                            <input type="hidden" name="update_role" value="1">
                                        </form>
                                    </td>
                                    <td>
                                        <form action="admin_users.php" method="POST">
                                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                            <select name="account_status" class="form-control" onchange="this.form.submit()">
                                                <option value="0" <?php echo $row['account_status'] == 0 ? 'selected' : ''; ?>>No</option>
                                                <option value="1" <?php echo $row['account_status'] == 1 ? 'selected' : ''; ?>>Yes</option>
                                            </select>
                                            <input type="hidden" name="update_status" value="1">
                                        </form>
                                    </td>
                                    <td class="user-actions">
                                        <form action="delete_user.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">Back</a></li>
                                <?php endif; ?>
                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#status-alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 3000);
        });
    </script>
</body>
</html>
