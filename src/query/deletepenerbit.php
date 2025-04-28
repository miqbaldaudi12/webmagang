<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include '../koneksi.php';

$id_mentor = $_GET['id_mentor'];

// First, check if there are any peserta associated with this mentor
$check_query = "SELECT COUNT(*) as peserta_count FROM peserta WHERE id_mentor = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("i", $id_mentor);
$check_stmt->execute();
$result = $check_stmt->get_result();
$row = $result->fetch_assoc();
$check_stmt->close();

if ($row['peserta_count'] > 0) {
  // There are peserta associated with this mentor, cannot delete
  $_SESSION['alert'] = [
    'type' => 'danger',
    'message' => 'Mentor tidak dapat dihapus karena masih terdapat peserta yang dibimbing oleh mentor ini!'
  ];
  header("Location: ../admin.php");
  exit();
}

// If we get here, it's safe to delete the mentor
$query = "DELETE FROM mentor WHERE id_mentor=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_mentor);

if ($stmt->execute()) {
  $_SESSION['alert'] = [
    'type' => 'success',
    'message' => 'Data mentor berhasil dihapus!'
  ];
} else {
  $_SESSION['alert'] = [
    'type' => 'danger',
    'message' => 'Data mentor gagal dihapus!'
  ];
}

$stmt->close();
$conn->close();

header("Location: ../admin.php");
exit();
