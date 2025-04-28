<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../koneksi.php';

$id_peserta = $_POST['id_peserta'];
$weeks = (int)$_POST['weeks'];

// Get current end date
$query = "SELECT tanggal_selesai FROM peserta WHERE id_peserta = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_peserta);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$current_end_date = new DateTime($row['tanggal_selesai']);

// Add weeks
$new_end_date = clone $current_end_date;
$new_end_date->modify("+{$weeks} weeks");

// Adjust to nearest Friday
$day_of_week = $new_end_date->format('w'); // 0 (Sunday) through 6 (Saturday)
if ($day_of_week != 5) { // 5 is Friday
    if ($day_of_week < 5) {
        $days_to_add = 5 - $day_of_week;
    } else {
        $days_to_add = 5 + (7 - $day_of_week);
    }
    $new_end_date->modify("+{$days_to_add} days");
}

// Update database
$query = "UPDATE peserta SET tanggal_selesai = ? WHERE id_peserta = ?";
$stmt = $conn->prepare($query);
$new_end_date_str = $new_end_date->format('Y-m-d');
$stmt->bind_param("si", $new_end_date_str, $id_peserta);

if ($stmt->execute()) {
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Durasi magang berhasil diperpanjang!'
    ];
} else {
    $_SESSION['alert'] = [
        'type' => 'danger',
        'message' => 'Gagal memperpanjang durasi magang!'
    ];
}

header("Location: ../admin.php");
exit();
