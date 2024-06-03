<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $delete_ips = $_POST['ip'];
    $allowed_ips = file('allowed_ips.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $allowed_ips = array_diff($allowed_ips, $delete_ips);
    file_put_contents('allowed_ips.txt', implode(PHP_EOL, $allowed_ips) . PHP_EOL);
    $message = "Seçilen IP adresleri başarıyla silindi.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $original_ip = $_POST['original_ip'];
    $new_ip = trim($_POST['new_ip']);

    if (filter_var($new_ip, FILTER_VALIDATE_IP)) {
        $allowed_ips = file('allowed_ips.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $key = array_search($original_ip, $allowed_ips);
        if ($key !== false) {
            $allowed_ips[$key] = $new_ip;
            file_put_contents('allowed_ips.txt', implode(PHP_EOL, $allowed_ips) . PHP_EOL);
            $message = "IP adresi başarıyla değiştirildi.";
        }
    } else {
        $message = "Geçersiz IP adresi.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $new_ip = trim($_POST['new_ip']);

    if (filter_var($new_ip, FILTER_VALIDATE_IP)) {
        file_put_contents('allowed_ips.txt', $new_ip . PHP_EOL, FILE_APPEND | LOCK_EX);
        $message = "IP adresi başarıyla eklendi.";
    } else {
        $message = "Geçersiz IP adresi.";
    }
}

$allowed_ips = file('allowed_ips.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>IP Adresi Yönetimi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">IP Adresi Yönetimi</h1>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="ip_ekle.php" method="post" class="mb-4">
            <div class="form-group">
                <label for="new_ip">Yeni IP Adresi:</label>
                <input type="text" id="new_ip" name="new_ip" class="form-control" required>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Ekle</button>
        </form>

        <h2>Ekli Olan IP Adresleri</h2>
        <form action="ip_ekle.php" method="post">
            <?php if (!empty($allowed_ips)): ?>
                <ul class="list-group mb-4">
                    <?php foreach ($allowed_ips as $ip): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <input type="checkbox" name="ip[]" value="<?php echo htmlspecialchars($ip); ?>">
                                <?php echo htmlspecialchars($ip); ?>
                            </span>
                            <span>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" data-ip="<?php echo htmlspecialchars($ip); ?>">Değiştir</button>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <button type="submit" name="delete" class="btn btn-danger">Sil</button>
            <?php else: ?>
                <p>Henüz ekli IP adresi yok.</p>
            <?php endif; ?>
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
                        <form id="editForm" action="ip_ekle.php" method="post">
                            <div class="form-group">
                                <label for="edit_ip">Yeni IP Adresi:</label>
                                <input type="text" id="edit_ip" name="new_ip" class="form-control" required>
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
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var ip = button.data('ip'); 
            var modal = $(this);
            modal.find('#original_ip').val(ip);
            modal.find('#edit_ip').val(ip);
        });
    </script>
</body>
</html>
