<?php
require_once 'db.php'; // Veritabanı bağlantısı

$message = '';

// Süresi dolmuş IP adreslerini kontrol etme ve silme işlevi
function checkAndDeleteExpiredIPs($pdo) {
    try {
        // Şu anki tarih ve saat
        $currentDateTime = date('Y-m-d H:i:s');
        
        // Süresi dolmuş IP adreslerini seçme sorgusu
        $stmt = $pdo->prepare("SELECT ip_address FROM ips WHERE expiry_date < :currentDateTime");
        $stmt->bindParam(':currentDateTime', $currentDateTime, PDO::PARAM_STR);
        $stmt->execute();
        $expiredIPs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Süresi dolmuş IP adreslerini silme sorgusu
        if (!empty($expiredIPs)) {
            $placeholders = rtrim(str_repeat('?,', count($expiredIPs)), ',');
            $deleteStmt = $pdo->prepare("DELETE FROM ips WHERE ip_address IN ($placeholders)");
            $deleteStmt->execute($expiredIPs);
            return true; // Başarıyla silindi
        } else {
            return false; // Silinecek IP adresi yok
        }
    } catch (PDOException $e) {
        return false; // Hata oluştu
    }
}

// İstek yönlendirme
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        // Silme işlemi
        $delete_ips = $_POST['ip'];
        if (!empty($delete_ips)) {
            $placeholders = rtrim(str_repeat('?,', count($delete_ips)), ',');
            $stmt = $pdo->prepare("DELETE FROM ips WHERE ip_address IN ($placeholders)");
            $stmt->execute($delete_ips);
            $message = "Seçilen IP adresleri başarıyla silindi.";
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['edit'])) {
        // Düzenleme işlemi
        $original_ip = $_POST['original_ip'];
        $new_ip = trim($_POST['new_ip']);

        if (filter_var($new_ip, FILTER_VALIDATE_IP)) {
            $stmt = $pdo->prepare("UPDATE ips SET ip_address = ? WHERE ip_address = ?");
            $stmt->execute([$new_ip, $original_ip]);
            $message = "IP adresi başarıyla değiştirildi.";
        } else {
            $message = "Geçersiz IP adresi.";
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['add'])) {
        // Ekleme işlemi
        $new_ip = trim($_POST['new_ip']);
        $expiry = $_POST['expiry'];

        if (filter_var($new_ip, FILTER_VALIDATE_IP)) {
            if ($expiry == 0) {
                // Süresiz olarak atanacak
                $expiry_date = null;
            } else {
                // Belirli bir süreyle atanacak
                $expiry_date = date('Y-m-d H:i:s', strtotime("+$expiry seconds"));
            }
            $stmt = $pdo->prepare("INSERT INTO ips (ip_address, expiry_date) VALUES (?, ?)");
            $stmt->execute([$new_ip, $expiry_date]);
            $message = "IP adresi başarıyla eklendi.";
        } else {
            $message = "Geçersiz IP adresi.";
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Süresi dolmuş IP adreslerini kontrol etme ve silme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['check_expiry'])) {
    if (checkAndDeleteExpiredIPs($pdo)) {
        $message = "Süresi dolan IP adresleri başarıyla silindi.";
    } else {
        $message = "Süresi dolan IP adresi bulunamadı veya silinemedi.";
    }
}

// Ekli IP adreslerini getirme
$stmt = $pdo->query("SELECT ip_address, TIMESTAMPDIFF(SECOND, NOW(), expiry_date) AS remaining_seconds FROM ips");
$allowed_ips = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Arama ve filtreleme parametrelerini al
$search_ip = isset($_GET['search_ip']) ? trim($_GET['search_ip']) : '';
$filter_expiry = isset($_GET['filter_expiry']) ? $_GET['filter_expiry'] : '';

// Ekli IP adreslerini getirme sorgusu
$query = "SELECT ip_address, TIMESTAMPDIFF(SECOND, NOW(), expiry_date) AS remaining_seconds FROM ips WHERE 1=1";

// Arama kriterini ekle
if (!empty($search_ip)) {
    $query .= " AND ip_address LIKE :search_ip";
}

// Filtre kriterini ekle
if ($filter_expiry === 'active') {
    $query .= " AND (expiry_date IS NULL OR expiry_date > NOW())";
} elseif ($filter_expiry === 'expired') {
    $query .= " AND expiry_date IS NOT NULL AND expiry_date < NOW()";
}

$stmt = $pdo->prepare($query);

// Arama parametresini bağla
if (!empty($search_ip)) {
    $stmt->bindValue(':search_ip', "%$search_ip%", PDO::PARAM_STR);
}

$stmt->execute();
$allowed_ips = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>IP Adresi Yönetimi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table thead {
            background-color: #007bff;
            color: #fff;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .modal-header {
            background-color: #007bff;
            color: #fff;
        }
        .modal-title {
            color: #fff;
        }

        .centered {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 centered">IP Adresi Yönetimi</h1>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form id="addForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="mb-4">
            <div class="form-group">
                <label for="new_ip">Yeni IP Adresi:</label>
                <input type="text" id="new_ip" name="new_ip" class="form-control" required>
                <div id="ipError" class="text-danger mt-2" style="display:none;">Geçersiz IP adresi.</div>
            </div>
            <div class="form-group">
                <label for="expiry">Süre (saniye cinsinden):</label>
                <input type="number" id="expiry" name="expiry" class="form-control" required>
            </div>
            <button type="submit" name="add" class="btn btn-success">Ekle</button>
        </form>
        <form id="searchForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="mb-4">
    <div class="form-group">
        <label for="search_ip">IP Adresi Ara:</label>
        <input type="text" id="search_ip" name="search_ip" class="form-control">
    </div>
    <div class="form-group">
        <label for="filter_expiry">Süre Filtresi:</label>
        <select id="filter_expiry" name="filter_expiry" class="form-control">
            <option value="">Hepsi</option>
            <option value="active">Aktif IP Adresleri</option>
            <option value="expired">Süresi Dolmuş IP Adresleri</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Ara</button>
</form>

        <h2>Ekli Olan IP Adresleri</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <?php if (!empty($allowed_ips)): ?>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">IP Adresi</th>
                    <th scope="col">Kalan Süre (saniye)</th>
                    <th scope="col">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allowed_ips as $ip): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ip['ip_address']); ?></td>
                        <td><?php echo htmlspecialchars($ip['remaining_seconds']); ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal" data-ip="<?php echo htmlspecialchars($ip['ip_address']); ?>">Düzenle</button>
                            <input type="checkbox" name="ip[]" value="<?php echo htmlspecialchars($ip['ip_address']); ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" name="delete" class="btn btn-danger">Sil</button>
    <?php else: ?>
        <p>Arama kriterlerine uygun IP adresi bulunamadı.</p>
    <?php endif; ?>
    <button type="submit" name="check_expiry" class="btn btn-primary">Süresi Dolan IP Adreslerini Kontrol Et</button>
</form>




        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">IP Adresini Değiştir</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="form-group">
                                <label for="edit_ip">Yeni IP Adresi:</label>
                                <input type="text" id="edit_ip" name="new_ip" class="form-control" required>
                                <div id="editIpError" class="text-danger mt-2" style="display:none;">Geçersiz IP adresi.</div>
                            </div>
                            <input type="hidden" name="original_ip" id="original_ip">
                            <button type="submit" name="edit" class="btn btn-primary">Kaydet</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function validateIp(ip) {
            const ipRegex = /^(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)$/;
            return ipRegex.test(ip);
        }

        document.getElementById('addForm').addEventListener('submit', function(event) {
            const newIp = document.getElementById('new_ip').value;
            if (!validateIp(newIp)) {
                document.getElementById('ipError').style.display = 'block';
                event.preventDefault();
            } else {
                document.getElementById('ipError').style.display = 'none';
            }
        });

        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var ip = button.data('ip'); 
            var modal = $(this);
            modal.find('#original_ip').val(ip);
            modal.find('#edit_ip').val(ip);
        });

        document.getElementById('editForm').addEventListener('submit', function(event) {
            const newIp = document.getElementById('edit_ip').value;
            if (!validateIp(newIp)) {
                document.getElementById('editIpError').style.display = 'block';
                event.preventDefault();
            } else {
                document.getElementById('editIpError').style.display = 'none';
            }
        });
    </script>
</body>
</html>
