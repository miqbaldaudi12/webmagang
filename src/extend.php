<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$id_peserta = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM peserta WHERE id_peserta='$id_peserta'");
$peserta = mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendataan Magang - Extend Durasi</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="def-background">
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand title" href="index.php">
                <img src="/assets/logotel.png" alt="Logo Telkom" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin.php">
                            <i class="bi bi-person-workspace"></i> Manage Interns
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pengadaan.php">
                            <i class="bi bi-clipboard-data"></i> Past Intern
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <?php if (isset($_SESSION['alert'])): ?>
            <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['alert']['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-calendar-plus"></i> Extend Durasi Magang
                </h5>
            </div>
            <div class="card-body">
                <form action="query/extend_duration.php" method="post">
                    <input type="hidden" name="id_peserta" value="<?php echo $peserta['id_peserta'] ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Peserta</label>
                                <input type="text" class="form-control" value="<?php echo $peserta['nama_peserta'] ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai Saat Ini</label>
                                <input type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($peserta['tanggal_selesai'])) ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="weeks" class="form-label">Tambah Durasi (Minggu)</label>
                                <input type="number" class="form-control" id="weeks" name="weeks" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai Baru</label>
                                <input type="text" class="form-control" id="new_end_date" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Simpan Perubahan
                                </button>
                                <a href="admin.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('weeks').addEventListener('input', function() {
            const weeks = parseInt(this.value) || 0;
            if (weeks > 0) {
                const currentEndDate = new Date('<?php echo $peserta['tanggal_selesai'] ?>');
                const newEndDate = new Date(currentEndDate);
                newEndDate.setDate(currentEndDate.getDate() + (weeks * 7));

                // Adjust to nearest Friday
                const day = newEndDate.getDay();
                if (day !== 5) { // 5 is Friday
                    const daysUntilFriday = (day < 5) ? (5 - day) : (5 + (7 - day));
                    newEndDate.setDate(newEndDate.getDate() + daysUntilFriday);
                }

                const formattedDate = newEndDate.getDate().toString().padStart(2, '0') + '/' +
                    (newEndDate.getMonth() + 1).toString().padStart(2, '0') + '/' +
                    newEndDate.getFullYear();

                document.getElementById('new_end_date').value = formattedDate;
            } else {
                document.getElementById('new_end_date').value = '';
            }
        });
    </script>
</body>

</html>