<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

$id_mentor = $_GET['id_mentor'];
$query = mysqli_query($conn, "SELECT * FROM mentor WHERE id_mentor='$id_mentor'");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendataan Magang - Edit Mentor</title>
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
          <i class="bi bi-pencil-square"></i> Edit Data Mentor
        </h5>
      </div>
      <div class="card-body">
        <?php while ($hasil = mysqli_fetch_array($query)) { ?>
          <form action="query/updatepenerbit.php" method="post">
            <input type="hidden" name="id_mentor" value="<?php echo $hasil['id_mentor'] ?>">
            <div class="row g-3">
              <div class="col-12">
                <div class="mb-3">
                  <label for="nama_mentor" class="form-label">Nama Mentor</label>
                  <input type="text" class="form-control" id="nama_mentor" name="nama_mentor"
                    value="<?php echo $hasil['nama_mentor'] ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="email_mentor" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email_mentor" name="email_mentor"
                    value="<?php echo $hasil['email_mentor'] ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="telp_mentor" class="form-label">No. Telp Mentor</label>
                  <input type="tel" class="form-control" id="telp_mentor" name="telp_mentor"
                    value="<?php echo $hasil['telp_mentor'] ?>"
                    pattern="0[0-9]{8,14}"
                    title="Nomor telepon harus diawali dengan 0 dan terdiri dari 9-15 digit"
                    maxlength="15"
                    required>
                  <div class="form-text">Format: 08xxxxxxxxxx (9-15 digit)</div>
                </div>
              </div>
              <div class="col-12">
                <div class="mb-3">
                  <label for="alamat_mentor" class="form-label">Alamat</label>
                  <textarea class="form-control" id="alamat_mentor" name="alamat_mentor"
                    rows="2" required><?php echo $hasil['alamat_mentor'] ?></textarea>
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
</body>

</html>