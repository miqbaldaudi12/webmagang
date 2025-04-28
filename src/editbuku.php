<?php
session_start();
include 'koneksi.php';
include 'query/fetchpenerbit.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

$id_peserta = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM peserta WHERE id_peserta='$id_peserta'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendataan Magang - Edit Peserta</title>
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
          <i class="bi bi-pencil-square"></i> Edit Data Peserta
        </h5>
      </div>
      <div class="card-body">
        <?php while ($hasil = mysqli_fetch_array($query)) { ?>
          <form action="query/updatebuku.php" method="post">
            <input type="hidden" name="id_peserta" value="<?php echo $hasil['id_peserta'] ?>">
            <div class="row g-3">
              <div class="col-12">
                <div class="mb-3">
                  <label for="nama_peserta" class="form-label">Nama Peserta</label>
                  <input type="text" class="form-control" id="nama_peserta" name="nama_peserta"
                    value="<?php echo $hasil['nama_peserta'] ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="email_peserta" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email_peserta" name="email_peserta"
                    value="<?php echo $hasil['email_peserta'] ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="telp_peserta" class="form-label">No. Telp Peserta</label>
                  <input type="tel" class="form-control" id="telp_peserta" name="telp_peserta"
                    value="<?php echo $hasil['telp_peserta'] ?>"
                    pattern="0[0-9]{8,14}"
                    title="Nomor telepon harus diawali dengan 0 dan terdiri dari 9-15 digit"
                    required>
                  <div class="form-text">Format: 08xxxxxxxxxx (9-15 digit)</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="alamat_peserta" class="form-label">Alamat</label>
                  <textarea class="form-control" id="alamat_peserta" name="alamat_peserta"
                    rows="2" required><?php echo $hasil['alamat_peserta'] ?></textarea>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="instansi" class="form-label">Instansi</label>
                  <input type="text" class="form-control" id="instansi" name="instansi"
                    value="<?php echo $hasil['instansi'] ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="id_mentor" class="form-label">ID Mentor</label>
                  <select class="form-select" id="id_mentor" name="id_mentor" required>
                    <?php foreach ($hasil_penerbit as $penerbit) : ?>
                      <option value="<?= $penerbit['id_mentor'] ?>"
                        <?= ($penerbit['id_mentor'] == $hasil['id_mentor']) ? 'selected' : '' ?>
                        data-nama="<?= $penerbit['nama_mentor'] ?>">
                        <?= $penerbit['id_mentor'] . ' - ' . $penerbit['nama_mentor'] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="mentor" class="form-label">Nama Mentor</label>
                  <input type="text" class="form-control" id="mentor" name="mentor"
                    value="<?php echo $hasil['mentor'] ?>" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                  <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                    value="<?php echo $hasil['tanggal_mulai'] ?>" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                  <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai"
                    value="<?php echo $hasil['tanggal_selesai'] ?>" readonly>
                  <div class="form-text text-muted">
                    <i class="bi bi-info-circle"></i> Untuk mengubah tanggal selesai, gunakan tombol Extend pada halaman utama
                  </div>
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
        <?php } ?>
      </div>
    </div>
  </div>

  <script>
    // Add event listener for mentor selection
    document.getElementById('id_mentor').addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const mentorName = selectedOption.getAttribute('data-nama');
      document.getElementById('mentor').value = mentorName;
    });
  </script>
</body>

</html>