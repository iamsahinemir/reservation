<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}//eeş
include('config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'YOUR_PATH\PHPMailer\src\Exception.php';
require 'YOUR_PATH\PHPMailer\src\PHPMailer.php';
require 'YOUR_PATH\PHPMailer\src\SMTP.php';

//mail için gerekli sistem.
//var olan bu konfigürasyon outlook mail sistemi için geçerli.


function sendEmail($toEmail, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp-mail.outlook.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'YOUR_MAIL';//Kendi mailinizi buraya ekleyin
        $mail->Password   = 'YOUR_PASSWORD'; // Kendi şifrenizi buraya ekleyin
        $mail->SMTPSecure = 'STARTTLS';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('YOUR_MAIL', 'Laboratuvar Rezervasyon Sistemi');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 8;
$offset = ($page - 1) * $limit;

$sql = "SELECT r.id, u.name AS user_name, d.name AS device_name, r.start_time, r.end_time, r.aciklama, r.status
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        JOIN devices d ON r.device_id = d.id
        ORDER BY u.name ASC
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$total_sql = "SELECT COUNT(*) as total FROM reservations";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve'])) {
        $reservation_id = $_POST['reservation_id'];
        $sql = "UPDATE reservations SET status = 'approved' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();

        $sql = "SELECT u.email, u.name, r.device_name FROM reservations r JOIN users u ON r.user_id = u.id WHERE r.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            $toEmail = $user['email'];
            $subject = "Rezervasyon Onayı"; //mail içeriği
            $body = "
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
                                <h2>Rezervasyon Onayı</h2>
                            </div>
                            <div class='content'>
                                <p>Sayın <strong>{$user['name']}</strong>,</p>
                                <p>Rezervasyon talebiniz onaylanmıştır. Aşağıda detayları bulabilirsiniz:</p>
                                <p><strong>Cihaz:</strong> {$user['device_name']}</p>
                                <p>Rezervasyonunuz ile ilgili herhangi bir sorun veya sorunuz olursa, lütfen bizimle iletişime geçmekten çekinmeyin.</p>
                                <p>Teşekkürler,</p>
                                <p><strong>Laboratuvar Rezervasyon Sistemi</strong></p>
                            </div>
                            <div class='footer'>
                                <p>Bu mesaj otomatik olarak oluşturulmuştur, lütfen yanıtlamayın.</p>
                            </div>
                        </div>
                    </body>
                    </html>";
            sendEmail($toEmail, $subject, $body);
        }
        $_SESSION['status'] = "Rezervasyon onaylandı.";
    } elseif (isset($_POST['reject'])) {
        $reservation_id = $_POST['reservation_id'];
        $sql = "SELECT u.email, u.name, r.device_name FROM reservations r JOIN users u ON r.user_id = u.id WHERE r.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            $toEmail = $user['email'];
            $subject = "Rezervasyon Reddi";//mail içeriği
            $body = "<html>
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
                                background-color: #f44336;
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
                                <h2>Rezervasyon Reddi</h2>
                            </div>
                            <div class='content'>
                                <p>Sayın <strong>{$user['name']}</strong>,</p>
                                <p>Maalesef rezervasyon talebiniz reddedilmiştir. Aşağıda detayları bulabilirsiniz:</p>
                                <p><strong>Cihaz:</strong> {$user['device_name']}</p>
                                <p>Eğer bu konuda herhangi bir sorunuz veya endişeniz varsa, lütfen bizimle iletişime geçin.</p>
                                <p>Teşekkürler,</p>
                                <p>esad.emir34@stu.khas.edu.tr</p>
                                <p><strong>Laboratuvar Rezervasyon Sistemi</strong></p>
                            </div>
                            <div class='footer'>
                                <p>Bu mesaj otomatik olarak oluşturulmuştur, lütfen yanıtlamayın.</p>
                            </div>
                        </div>
                    </body>
                    </html>";
            sendEmail($toEmail, $subject, $body);

            // Veritabanına 'rejected' olarak güncelleme sorgusu
        $sql = "UPDATE reservations SET status = 'rejected' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        }
        $_SESSION['status'] = "Rezervasyon reddedildi.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervasyon Yönetimi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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
}

.content h1 {
    position: relative; /* Position absolute kaldırıldı */
    margin-top: 20px;
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
            transition: background 0.3s;
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
        .navbar .language-buttons {
        padding: 10px;
        display: flex;
        justify-content: center; /* Butonları ortaya hizalar */
        position: absolute;
        bottom: 80px; /* Dil butonlarının profil bölümünün üstünde kalması için */
        width: 100%;
        left:0;
    }
    .navbar .language-buttons button {
        border: none;
        padding: 12px 45px; /* Butonların genişliğini eski haline getirir */
        margin: 10px 5px;
        background-color: #4B79A1;
        color: #ffffff;
        font-size: 12px;
        cursor: pointer;
        transition: transform 80ms ease-in, background-color 0.3s ease;
        border-radius: 25px; /* Köşeleri yuvarlatır */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Hafif bir gölge ekler */
    }
    .navbar .language-buttons button:hover {
        background-color: #365f91; /* Hover durumunda buton rengini koyulaştırır */
        transform: scale(1.05); /* Hover durumunda butonu hafifçe büyütür */
    }
    @media (max-width: 768px) {
    .content {
        margin-left: 0;
        width: 100%;
        padding: 10px;
    }

    .content h1 {
        margin-top: 0;
        margin-bottom: 20px;
    }

    table {
        font-size: 14px;
        width: 100%; /* Tablonun tam genişliğini kaplamasını sağlar */
    }
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
            <div class="language-buttons">
            <button onclick="switchLanguage('tr')">TR</button>
            <button onclick="switchLanguage('en')">ENG</button>
            </div>
        </div>
        <div class="profile">
            <a href="logout.php">Çıkış</a>
        </div>
    </div>

    <div class="container content">
        <h1>Rezervasyon Yönetimi</h1>

        <!-- Başarı veya Hata Mesajları -->
        <?php if (isset($_SESSION['status'])): ?>
            <div class="alert <?php echo strpos($_SESSION['status'], 'başarıyla') !== false ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo $_SESSION['status']; unset($_SESSION['status']); ?>
            </div>
        <?php endif; ?>

        <table class="table table-bordered mt-3">
            <thead class="thead-light">
                <tr>
                    <th>Kullanıcı</th>
                    <th>Cihaz</th>
                    <th>Başlangıç Saati</th>
                    <th>Bitiş Saati</th>
                    <th>Açıklama</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['device_name']; ?></td>
                    <td><?php echo $row['start_time']; ?></td>
                    <td><?php echo $row['end_time']; ?></td>
                    <td><?php echo $row['aciklama']; ?></td>
                    <td><?php echo $row['status'] == 'approved' ? 'Onaylandı' : ($row['status'] == 'rejected' ? 'Reddedildi' : 'Bekliyor'); ?></td>
                    <td>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="approve" class="btn btn-success">Onayla</button>
                        </form>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="reject" class="btn btn-danger">Red</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">Geri</a></li>
                <?php endif; ?>
                <?php if ($page < $total_pages): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">İleri</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 3000);
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
