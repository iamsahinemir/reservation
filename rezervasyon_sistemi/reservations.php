<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    echo "Kullanıcı oturumu bulunamadı. Lütfen giriş yapınız.";
    exit();//eeş
}

$user_id = $_SESSION['user_id'];
$limit = 5; // Her sayfada gösterilecek rezervasyon sayısı
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Toplam rezervasyon sayısını bulma
$total_sql = "SELECT COUNT(*) FROM reservations WHERE user_id = ?";
$total_stmt = $conn->prepare($total_sql);
$total_stmt->bind_param("i", $user_id);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_rows = $total_result->fetch_row()[0];
$total_pages = ceil($total_rows / $limit);

// Rezervasyonları getirme
$sql = "SELECT * FROM reservations WHERE user_id = ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Rezervasyonu silme işlemi
if (isset($_POST['delete_reservation'])) {
    $reservation_id = $_POST['reservation_id'];
    $delete_sql = "DELETE FROM reservations WHERE id = ? AND user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $reservation_id, $user_id);
    if ($delete_stmt->execute()) {
        echo "Rezervasyon başarıyla silindi.";
        header("Location: reservations.php");
        exit();
    } else {
        echo "Rezervasyon silinirken bir hata oluştu.";
    }
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
            position: relative; /* Added for absolute positioning of dropdown */
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
            bottom: 100%
        }

        .navbar .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            top: 100%
        }

        .navbar .dropdown-content a:hover {
            background-color: #c1cdff;
            color: #3056ff;
        }

        /* Show dropdown on hover */
        .navbar .profile:hover .dropdown-content {
            display: block;
        }

        .container {
    margin-left: 250px;
    padding: 20px;
    width: calc(100% - 250px);
    min-height: 100vh; /* Navbar ile aynı yüksekliği koruyun */
    box-sizing: border-box;
    overflow-x: auto; /* Taşan içeriğin yatay kaydırma çubuğu olması için */
}

.container h1 {
    font-size: 2em;
    margin-bottom: 20px;
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
        .pagination {
    text-align: center;
    margin-top: 20px;
}

.pagination a {
    margin: 0 10px;
    padding: 10px 15px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.pagination a:hover {
    background-color: #0056b3;
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
            <a href="#" >Profil</a>
            <div class="dropdown-content">
                <a href="profile.php">Bilgilerim</a>
                <a href="reservations.php">Rezervasyonlarım</a>
                <a href="logout.php">Çıkış</a>
            </div>
        </div>
    </div>
        <div class="container">
        <h1>Rezervasyonlarım</h1>

        <table>
            <tr>
                <th>Rezervasyon Tarihi</th>
                <th>Rezervasyon Bitiş Tarihi</th>
                <th>Kullanılacak Cihaz</th>
                <th>Durum</th>
                <th>İşlem</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['start_time']; ?></td>
                    <td><?php echo $row['end_time']; ?></td>
                    <td><?php echo $row['device_name']; ?></td>
                    <td>
                        <?php
                        echo $row['status'] == 'approved' ? 'Onaylandı' :
                            ($row['status'] == 'rejected' ? 'Reddedildi' : 'Bekliyor');
                        ?>
                    </td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete_reservation">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <div class="pagination">
            <?php if ($page > 1) { ?>
                <a href="?page=<?php echo $page - 1; ?>" class="prev-button">Geri</a>
            <?php } ?>
            
            <?php if ($page < $total_pages) { ?>
                <a href="?page=<?php echo $page + 1; ?>" class="next-button">İleri</a>
            <?php } ?>
        </div>
    </div>

</body>
</html>
