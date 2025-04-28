<!DOCTYPE html>
<html lang="en">
<?php
include 'query/fetchbuku.php';
include 'query/fetchpenerbit.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendataan Magang - Manage Interns</title>
  <link rel="stylesheet" href="styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="def-background mb-4">
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

    <!-- Peserta Section -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Peserta Magang</h5>
        <div class="d-flex gap-2">
          <a href="tambah.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Peserta
          </a>
          <a href="query/export_peserta.php" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="input-group mb-3">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input type="text" class="form-control" id="searchPeserta" placeholder="Cari peserta...">
        </div>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">ID Peserta</th>
                <th scope="col">Nama Peserta</th>
                <th scope="col">Email</th>
                <th scope="col">No. Telp</th>
                <th scope="col">Alamat</th>
                <th scope="col">Instansi</th>
                <th scope="col">Mentor</th>
                <th scope="col">Tgl Mulai</th>
                <th scope="col">Tgl Selesai</th>
                <th scope="col">Sisa Waktu</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody id="tbodybuku">
              <?php if (empty($hasil)) : ?>
                <tr>
                  <td colspan="11" class="text-center">Tidak ada peserta</td>
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
                    <td><?= $b['telp_peserta'] ?></td>
                    <td><?= $b['alamat_peserta'] ?></td>
                    <td><?= $b['instansi'] ?></td>
                    <td><?= $b['mentor'] ?></td>
                    <td><?= date('d/m/Y', strtotime($b['tanggal_mulai'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($b['tanggal_selesai'])) ?></td>
                    <td><span class="badge bg-<?= $status_class ?>"><?= $remaining_text ?></span></td>
                    <td>
                      <div class="btn-group">
                        <a href="editbuku.php?id=<?= $b['id_peserta'] ?>" class="btn btn-sm btn-primary">
                          <i class="bi bi-pencil"></i>
                        </a>
                        <a href="extend.php?id=<?= $b['id_peserta'] ?>" class="btn btn-sm btn-info">
                          <i class="bi bi-calendar-plus"></i>
                        </a>
                        <a href="query/move_to_history.php?id=<?= $b['id_peserta'] ?>" class="btn btn-sm btn-success"
                          onclick="return confirm('Apakah Anda yakin ingin memindahkan peserta ini ke history?')">
                          <i class="bi bi-check-lg"></i>
                        </a>
                        <a href="query/deletebuku.php?id=<?= $b['id_peserta'] ?>" class="btn btn-sm btn-danger"
                          onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini?')">
                          <i class="bi bi-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Mentor Section -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Mentor</h5>
        <div class="d-flex gap-2">
          <a href="tambahpenerbit.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Mentor
          </a>
          <a href="query/export_mentor.php" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="input-group mb-3">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input type="text" class="form-control" id="searchMentor" placeholder="Cari mentor...">
        </div>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">ID Mentor</th>
                <th scope="col">Nama</th>
                <th scope="col">Email</th>
                <th scope="col">No. Telp</th>
                <th scope="col">Alamat</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody id="tbodyMentor">
              <?php if (empty($hasil_penerbit)) : ?>
                <tr>
                  <td colspan="6" class="text-center">Tidak ada mentor</td>
                </tr>
              <?php else : ?>
                <?php foreach ($hasil_penerbit as $p) : ?>
                  <tr>
                    <td><?= $p['id_mentor'] ?></td>
                    <td><?= $p['nama_mentor'] ?></td>
                    <td><?= $p['email_mentor'] ?></td>
                    <td><?= $p['telp_mentor'] ?></td>
                    <td><?= $p['alamat_mentor'] ?></td>
                    <td>
                      <div class="btn-group">
                        <a href="editpenerbit.php?id_mentor=<?= $p['id_mentor'] ?>" class="btn btn-sm btn-primary">
                          <i class="bi bi-pencil"></i>
                        </a>
                        <a href="query/deletepenerbit.php?id_mentor=<?= $p['id_mentor'] ?>" class="btn btn-sm btn-danger"
                          onclick="return confirm('Apakah Anda yakin ingin menghapus mentor ini?')">
                          <i class="bi bi-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Search functionality for Peserta table
    document.getElementById('searchPeserta').addEventListener('input', function() {
      const searchValue = this.value.toLowerCase();
      const rows = document.querySelectorAll('#tbodybuku tr');

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
      });
    });

    // Search functionality for Mentor table
    document.getElementById('searchMentor').addEventListener('input', function() {
      const searchValue = this.value.toLowerCase();
      const rows = document.querySelectorAll('#tbodyMentor tr');

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
      });
    });
  </script>
</body>

</html>