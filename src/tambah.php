<!DOCTYPE html>
<html lang="en">

<?php
include 'koneksi.php';
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
  <title>Pendataan Magang - Input Peserta</title>
  <link rel="stylesheet" href="styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="def-background">
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand title" href="index.php">
        <img src="/assets/logotel.png" />
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="admin.php">Admin</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pengadaan.php">Kebutuhan Peserta</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="p-4 m-auto w-75 h-auto mt-5 rounded-3 table-responsive card mb-5">
    <h5 class="title" style="color:black;">Tambah Peserta</h5>
    <form action="query/tambahbuku.php" method="post" onsubmit="return validateForm()">
      <div class="mb-3">
        <label for="id_peserta" class="form-label">ID Peserta</label>
        <input type="text" required class="form-control" id="id_peserta" name="id_peserta">
      </div>
      <div class="mb-3">
        <label for="nama_peserta" class="form-label">Nama Peserta</label>
        <input type="text" required class="form-control" id="nama_peserta" name="nama_peserta">
      </div>
      <div class="mb-3">
        <label for="email_peserta" class="form-label">Email Peserta</label>
        <input type="email" required class="form-control" id="email_peserta" name="email_peserta">
      </div>
      <div class="mb-3">
        <label for="alamat_peserta" class="form-label">Alamat</label>
        <input type="text" required class="form-control" id="alamat_peserta" name="alamat_peserta">
      </div>
      <div class="mb-3">
        <label for="instansi" class="form-label">Instansi</label>
        <input type="text" required class="form-control" id="instansi" name="instansi">
      </div>
      <div class="mb-3">
        <label for="id_mentor" class="form-label">ID Mentor</label>
        <select class="form-select" id="id_mentor" name="id_mentor" onchange="updateMentorName()">
          <option value="">Pilih ID Mentor</option>
          <?php
          if ($hasil_penerbit) {
              foreach ($hasil_penerbit as $p) {
                  echo '<option value="' . $p['id_mentor'] . '" data-mentor-name="' . $p['nama_mentor'] . '">' . $p['id_mentor'] . '</option>';
              }
          } else {
              echo '<option value="">Tidak ada mentor</option>';
          }
          ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="mentor" class="form-label">Mentor</label>
        <input type="text" class="form-control" id="mentor" name="mentor" readonly>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>

  <script>
    function updateMentorName() {
      const idMentorDropdown = document.getElementById('id_mentor');
      const mentorInput = document.getElementById('mentor');
      const selectedOption = idMentorDropdown.options[idMentorDropdown.selectedIndex];
      const mentorName = selectedOption.getAttribute('data-mentor-name');
      mentorInput.value = mentorName || '';
    }

    function validateForm() {
      let idMentor = document.getElementById("id_mentor").value;
      if (idMentor === "") {
        if (!confirm("Anda tidak memilih mentor. Lanjutkan tanpa mentor?")) {
          return false;
        }
      }
      return true;
    }
  </script>

</body>

</html>
