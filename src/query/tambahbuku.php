<?php
include '../koneksi.php';

$id_peserta = $_POST['id_peserta'];
$nama_peserta = $_POST['nama_peserta'];
$email_peserta = $_POST['email_peserta'];
$alamat_peserta = $_POST['alamat_peserta'];
$instansi = $_POST['instansi'];

// Pastikan `id_mentor` ada dan tidak kosong
$id_mentor = isset($_POST['id_mentor']) && $_POST['id_mentor'] !== '' ? (int) $_POST['id_mentor'] : NULL;
$mentor = isset($_POST['mentor']) ? $_POST['mentor'] : '';

// Periksa apakah ID Peserta sudah ada
$query = "SELECT id_peserta FROM peserta WHERE id_peserta = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id_peserta);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('ID Peserta sudah ada dalam database!');window.location.href='../tambah.php';</script>";
} else {
    var_dump($id_peserta, $nama_peserta, $email_peserta, $alamat_peserta, $instansi, $id_mentor, $mentor);
    // Query untuk menambahkan data
    $query = "INSERT INTO peserta (id_peserta, nama_peserta, email_peserta, alamat_peserta, instansi, id_mentor, mentor) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    if ($id_mentor === NULL) {
        $stmt->bind_param("sssss s", $id_peserta, $nama_peserta, $email_peserta, $alamat_peserta, $instansi, $mentor);
    } else {
        $stmt->bind_param("sssssis", $id_peserta, $nama_peserta, $email_peserta, $alamat_peserta, $instansi, $id_mentor, $mentor);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Data Peserta berhasil ditambahkan!');window.location.href='../admin.php';</script>";
    } else {
        echo "<script>alert('Data Peserta gagal ditambahkan!');window.location.href='../admin.php';</script>";
    }
}

$stmt->close();
$conn->close();
?>
