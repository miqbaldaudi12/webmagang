<!DOCTYPE html>
<html lang="en">

<?php
include 'koneksi.php';
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
  <title>UNIBOOKSTORE - Edit Mentor</title>
  <link rel="stylesheet" href="styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body class="def-background">
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand  title" href="index.php">
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


  <?php
  include 'koneksi.php';

  $id_mentor = $_GET['id_mentor'];

  $query = mysqli_query($conn, "SELECT * FROM mentor WHERE id_mentor='$id_mentor'");
  ?>
  <div class="m-auto w-75 h-auto mt-5 rounded-3 table-responsive card">
    <?php

    while ($hasil = mysqli_fetch_array($query)) {
    ?>
      <form class="p-4" action="query/updatepenerbit.php" method="post">
        <div class="mb-3">
          <label for="id_mentor" class="form-label">ID Mentor</label>
          <input type="text" class="form-control" id="id_mentor" name="id_mentor" value="<?php echo $hasil['id_mentor']; ?>" readonly>
        </div>
        <div class="mb-3">
          <label for="nama_mentor" class="form-label">Nama Mentor</label>
          <input type="text" class="form-control" id="nama_mentor" name="nama_mentor" value="<?php echo $hasil['nama_mentor']; ?>">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" value="<?php echo $hasil['email_mentor']; ?>">
        </div>
        <div class="mb-3">
          <label for="alamat_mentor" class="form-label">Alamat</label>
          <input type="text" class="form-control" id="alamat_mentor" name="alamat_mentor" value="<?php echo $hasil['alamat_mentor']; ?>">
        </div>
        
        <button type="submit" class="btn btn-primary">Update</button>
      </form>
    <?php } ?>
  </div>


</body>

</html>