<!DOCTYPE html>
<html lang="en">
<?php
include 'query/fetchbuku.php';
require_once 'koneksi.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

// Get total counts using direct queries
$query_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM peserta");
$total_peserta = mysqli_fetch_assoc($query_total)['total'];

// For now, consider all peserta as active since there's no status column
$active_count = $total_peserta;

$query_mentor = mysqli_query($conn, "SELECT COUNT(*) as total FROM mentor");
$total_mentor = mysqli_fetch_assoc($query_mentor)['total'];
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendataan Magang - Dashboard</title>
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
            <a class="nav-link active" aria-current="page" href="index.php">
              <i class="bi bi-house-door"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="admin.php">
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
    <div class="row mb-4">
      <div class="col-12">
        <h2 class="title mb-4">Dashboard</h2>
      </div>
      <div class="col-md-4">
        <div class="stats-card">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h3><?php echo $total_peserta; ?></h3>
              <p>Total Peserta Magang</p>
            </div>
            <i class="bi bi-people fs-1 text-primary"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stats-card">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h3><?php echo $active_count; ?></h3>
              <p>Peserta Aktif</p>
            </div>
            <i class="bi bi-person-check fs-1 text-success"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stats-card">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h3><?php echo $total_mentor; ?></h3>
              <p>Total Mentor</p>
            </div>
            <i class="bi bi-person-video3 fs-1 text-info"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Peserta Magang</h5>
        <div class="d-flex gap-2">
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" id="searchInput" placeholder="Cari peserta...">
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">ID Peserta</th>
              <th scope="col">Nama Peserta</th>
              <th scope="col">Email Peserta</th>
              <th scope="col">Alamat</th>
              <th scope="col">Instansi</th>
              <th scope="col">Mentor</th>
              <th scope="col">Tgl Mulai</th>
              <th scope="col">Tgl Selesai</th>
              <th scope="col">Sisa Waktu</th>
            </tr>
          </thead>
          <tbody id="tbodybuku">
            <?php if (empty($hasil)) : ?>
              <tr>
                <td colspan="9" class="text-center">Tidak ada peserta</td>
              </tr>
            <?php else : ?>
              <?php foreach ($hasil as $b) : ?>
                <?php
                $today = new DateTime();
                $end_date = new DateTime($b['tanggal_selesai']);
                $interval = $today->diff($end_date);
                $remaining_days = $interval->format('%R%a');

                // Determine status class based on remaining days
                if ($remaining_days <= 0) {
                  $status_class = 'success';
                  $remaining_text = 'Selesai';
                } else if ($remaining_days <= 7) {
                  $status_class = 'warning';
                  $remaining_text = $remaining_days . ' hari';
                } else if ($remaining_days <= 14) {
                  $status_class = 'info';
                  $remaining_text = $remaining_days . ' hari';
                } else {
                  $status_class = 'primary';
                  $remaining_text = $remaining_days . ' hari';
                }
                ?>
                <tr>
                  <td><?= $b['id_peserta'] ?></td>
                  <td><?= $b['nama_peserta'] ?></td>
                  <td><?= $b['email_peserta'] ?></td>
                  <td><?= $b['alamat_peserta'] ?></td>
                  <td><?= $b['instansi'] ?></td>
                  <td><?= $b['mentor'] ?></td>
                  <td><?= date('d/m/Y', strtotime($b['tanggal_mulai'])) ?></td>
                  <td><?= date('d/m/Y', strtotime($b['tanggal_selesai'])) ?></td>
                  <td><span class="badge bg-<?= $status_class ?>"><?= $remaining_text ?></span></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#tbodybuku tr');

    searchInput.addEventListener('input', function() {
      const searchValue = this.value.toLowerCase();
      tableRows.forEach(function(row) {
        const rowData = row.textContent.toLowerCase();
        row.style.display = rowData.includes(searchValue) ? '' : 'none';
      });
    });
  </script>

</body>

</html>