<!DOCTYPE html>
<html lang="en">

<?php
include 'query/fetchbuku.php';
include 'query/fetchpenerbit.php';
require_once 'koneksi.php'; // Menghubungkan ke database
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendataan Magang - Admin</title>
  <link rel="stylesheet" href="styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body class="def-background mb-3">
  <nav class="navbar bg-primary navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand title" href="index.php">
        <img src="/assets/logotel.png"/>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="index.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="admin.php">Admin</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pengadaan.php">Kebutuhan Peserta</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php"> Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- =======================NavEnd======================== -->

  <!-- =======================Content======================== -->



  <div class="grid gap-2 mt-5 w-75 m-auto h-auto flex-col-med card p-3 rounded-3">
    <h4 class="text-center title">Daftar Peserta Magang</h4>
    <a href="tambah.php">
      <button class="btn btn-primary">Tambah Peserta Magang</button>
    </a>
    <div class=" rounded-3 table-responsive card">
      <table class="table table-striped rounded-5 table-bordered">
        <thead>
          <tr>
            <th scope="col">ID Peserta</th>
            <th scope="col">Nama Peserta</th>
            <th scope="col">Email Peserta</th>
            <th scope="col">Alamat</th>
            <th scope="col">Instansi</th>
            <th scope="col">Mentor</th>
            <th scope="col">Aksi</th>

          </tr>
        </thead>
        <tbody id="tbodybuku">
          <?php if (empty($hasil)) : ?>
            <tr>
              <td colspan="7">Tidak ada peserta</td>
            </tr>
          <?php else : ?>
            <?php foreach ($hasil as $b) : ?>
              <tr id="trbuku">
                <td><?= $b['id_peserta'] ?></td>
                <td><?= $b['nama_peserta'] ?></td>
                <td><?= $b['email_peserta'] ?></td>
                <td><?= $b['alamat_peserta'] ?></td>
                <td><?= $b['instansi'] ?></td>
                <td><?= $b['mentor'] ?></td>
                <td>
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="editbuku.php?id=<?= $b['id_peserta'] ?>">
                      <button type="button" class="btn btn-primary">Edit</button>
                    </a>
                    <a href="query/deletebuku.php?id=<?= $b['id_peserta'] ?>">
                      <button type="button" class="btn btn-danger">Delete</button>
                    </a>
                    <a href="">
                      <button type="button" class="btn btn-info">Extend</button>
                    </a>
                    <a href="">
                      <button type="button" class="btn btn-success">Finish</button>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>

        </tbody>
      </table>
      <a href="query/export_peserta.php" class="btn btn-primary">Export Data Peserta ke Excel</a>
    </div>
  </div>


  <div class="grid gap-2 mt-5 w-75 m-auto h-auto flex-col-med card p-3 rounded-3">
    <h4 class="text-center title">Daftar Mentor</h4>
    <a href="tambahpenerbit.php">
      <button class="btn btn-primary" style="width:fit-content;">Tambah Mentor</button>
    </a>
    <div class=" rounded-3 table-responsive card">
      <table class="table table-striped rounded-5 table-bordered">
        <thead>
          <tr>
            <th scope="col">ID Mentor</th>
            <th scope="col">Nama Mentor</th>
            <th scope="col">Email Mentor</th>
            <th scope="col">Alamat</th>
            <th scope="col">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($hasil_penerbit)) : ?>
            <tr>
              <td colspan="7">Tidak ada mentor</td>
            </tr>
          <?php else : ?>
            <?php foreach ($hasil_penerbit as $b) : ?>
              <tr>
                <td><?= $b['id_mentor'] ?></td>
                <td><?= $b['nama_mentor'] ?></td>
                <td><?= $b['email_mentor'] ?></td>
                <td><?= $b['alamat_mentor'] ?></td>
                <td>
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="editpenerbit.php?id_mentor=<?= $b['id_mentor'] ?>">
                      <button type="button" class="btn btn-primary">Edit</button>
                    </a>
                    <a href="query/deletepenerbit.php?id_mentor=<?= $b['id_mentor'] ?>">
                      <button type="button" class="btn btn-danger">Delete</button>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      <a href="query/export_mentor.php" class="btn btn-primary">Export Data Mentor ke Excel</a>

    </div>
  </div>



  <script>
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#tbodybuku tr');

    searchInput.addEventListener('input', function() {
      const searchValue = this.value.toLowerCase();

      tableRows.forEach(function(row) {
        const rowData = row.textContent.toLowerCase();

        if (rowData.includes(searchValue)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  </script>

</body>

</html>